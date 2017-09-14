<?php

namespace Admin\Controller;

use Application\Entity\Article;
use Application\Entity\Category;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Form\ArticleForm;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Application\Service\FormServiceInterface;
use Zend\Form\FormInterface;
use Authentication\Service\ValidationServiceInterface;

class ArticleController extends AbstractActionController
{
    private $entityManager;
    private $articleForm;
    private $formService;
    private $validationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleForm $articleForm,
        FormServiceInterface $formService,
        ValidationServiceInterface $validationService
    ) {
        $this->entityManager     = $entityManager;
        $this->articleForm       = $articleForm;
        $this->formService       = $formService;
        $this->validationService = $validationService;
    }

    public function indexAction()
    {
        $article = new Article();
        $form = $this->formService->getAnnotationForm($this->entityManager, $article);

        $category = $this->entityManager->getRepository(Category::class)->findAll();

        $articlesQueryBuilder = $this->entityManager
                                     ->getRepository(Article::class)
                                     ->getArticlesQueryBuilder();

        $adapter = new DoctrinePaginator(new ORMPaginator($articlesQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        $itemCountPerPage = 10;
        $paginator->setItemCountPerPage($itemCountPerPage);

        $pageNumber = (int)$paginator->getCurrentPageNumber();

        return new ViewModel([
            'form'        => $form,
            'category'    => $category,
            'articles'    => $paginator,
            'pageNumber'  => $pageNumber,
            'cnt'         => 0,
            'articlesCnt' => ($currentPageNumber - 1) * $itemCountPerPage,
        ]);
    }

    public function addAction()
    {
        $article = new Article();

        /* For annotation form */
        $form = $this->formService->getAnnotationForm($this->entityManager, $article);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        /* For ordinary form */
        /*$form = $this->articleForm;
        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($article);*/

        $request = $this->getRequest();
        if ($request->isPost()) {

            /* In order it was impossible to add an article to a category which has a child category */
            $categoryId = (int)$request->getPost('category');
            $childCategories = $this->entityManager->getRepository(Category::class)->findBy(['parentId' => $categoryId]);

            if ($childCategories) {
                $this->flashMessenger()->addErrorMessage('Cannot add article to category which has child category');

                // in order to get flashMessenger on add page
                $this->redirect()->refresh();
                
                return ['form' => $form]; die;
            }
            /* End block */

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            if ($form->isValid()) {

                $repository = $this->entityManager->getRepository(Article::class);

                if ($this->validationService->isObjectExists($repository, $article->getTitle(), ['title'])) {
                    $titleExists = 'Article with title "' . $article->getTitle() . '" exists already';
                    $form->get('title')->setMessages(['titleExists' => $titleExists]);
                    return ['form' => $form]; die;
                }

                $article = $form->getData();
                if ($fileName) {
                    $article->setImage('/img/article/' . $fileName);
                }

                $this->entityManager->persist($article);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Article added');

                return $this->redirect()->toRoute('admin/articles');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $id         = (int)$this->params()->fromRoute('id', 0);
        $article    = $this->entityManager->getRepository(Article::class)->find($id);

        if (! $pageNumber || ! $id || ! $article) {
            return $this->notFoundAction();
        }

        /* For annotation form */
        $form = $this->formService->getAnnotationForm($this->entityManager, $article);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        /* For ordinary form */
        /*$form = $this->articleForm;
        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($article);*/

        $request = $this->getRequest();
        if ($request->isPost()) {

            /* In order it was impossible to add an article to a category which has a child category */
            $categoryId = (int)$request->getPost('category');
            $childCategories = $this->entityManager->getRepository(Category::class)->findBy(['parentId' => $categoryId]);

            if ($childCategories) {
                $this->flashMessenger()->addErrorMessage('Cannot add article to category which has child category');

                // in order to get flashMessenger on add page
                $this->redirect()->refresh();

                return [
                    'form'       => $form,
                    'pageNumber' => $pageNumber,
                    'id'         => $id,
                    'article'    => $article,
                ];
                die;
            }
            /* End block */

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            $articleTitleOld = $article->getTitle();
            $articleTitleNew = trim(strip_tags($form->get('title')->getValue()));

            $repository = $this->entityManager->getRepository(Article::class);

            if ($repository->findBy(['title' => $articleTitleNew]) && $articleTitleNew !== $articleTitleOld) {
                $titleExists = 'Article with title "' . $articleTitleNew . '" exists already';
                $form->get('title')->setMessages(['titleExists' => $titleExists]);
            }

            if ($form->isValid() && empty($form->getMessages())) {
                $article = $form->getData();

                /* Block for replacing old image */
                if ($fileName) {
                    $oldImage = $article->getImage();
                    if (is_file(getcwd() . '/public_html' . $oldImage)) {
                        unlink(getcwd() . '/public_html' . $oldImage);
                    }

                    $article->setImage('/img/article/' . $fileName);
                }
                /* End block */

                $this->entityManager->persist($article);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Article edited');

                return $this->redirect()->toRoute('admin/articles', ['page' => $pageNumber]);
            }
        }

        return new ViewModel([
            'form'       => $form,
            'pageNumber' => $pageNumber,
            'id'         => $id,
            'article'    => $article,
        ]);
    }

    public function deleteAction()
    {
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $id         = (int)$this->params()->fromRoute('id', 0);
        $article    = $this->entityManager->getRepository(Article::class)->find($id);
        $request    = $this->getRequest();

        if (! $request->isPost() || ! $pageNumber || ! $id || ! $article) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $article);
        $form->setValidationGroup(['csrf']);

        $form->setData($request->getPost());

        if ($form->isValid()) {
            $article = $form->getData();

            /* Block for deletion article image on server */
            if ($article) {
                if (is_file(getcwd() . '/public_html' . $article->getImage())) {
                    unlink(getcwd() . '/public_html' . $article->getImage());
                }
            }
            /* End block */

            $this->entityManager->remove($article);
            $this->entityManager->flush();

            $this->flashMessenger()->setNamespace('success')->addMessage('Article deleted');

            return $this->redirect()->toRoute('admin/articles', ['page' => $pageNumber]);
        }

        return $this->notFoundAction();
    }
}
