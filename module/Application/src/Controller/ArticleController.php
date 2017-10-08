<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;
use Application\Entity\ArticleLike;
use Application\Entity\ArticleDislike;
use Application\Service\FormServiceInterface;
use Application\Entity\Comment;

class ArticleController extends AbstractActionController
{
    private $entityManager;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService   = $formService;
    }

    public function indexAction()
    {
        $articleLike    = false;
        $articleDislike = false;

        $articleId = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $article = $this->entityManager->getRepository(Article::class)->find($articleId);

        /* Like-dislike block */
        /* To use the Identity plugin, necessary to install zendframework/zend-mvc-plugin-identity */
        if ($this->identity()) {
            $userId = (int)$this->identity()->getId();

            $articleLikeArray = $this->entityManager
                                     ->getRepository(ArticleLike::class)
                                     ->findBy(['article' => $articleId, 'user' => $userId]);

            if ($articleLikeArray) $articleLike = true;

            $articleDislikeArray = $this->entityManager
                                        ->getRepository(ArticleDislike::class)
                                        ->findBy(['article' => $articleId, 'user' => $userId]);

            if ($articleDislikeArray) $articleDislike = true;
        }
        /* End like-dislike block */

        /* Comments block */
        $comment = new Comment();
        $form = $this->formService->getAnnotationForm($this->entityManager, $comment);

        $comments = $this->entityManager->getRepository(Comment::class)->findBy(['article' => $articleId]);
        /* End comments block */

        $referer = $this->getRequest()->getServer('HTTP_REFERER');

        return new ViewModel([
            'article'  => $article,
            'referer'  => $referer,
            'comments' => $comments,
            'form'     => $form,
            'articleLike'    => $articleLike,
            'articleDislike' => $articleDislike,
        ]);
    }

    public function addLikeAction()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $userId = (int)$this->identity()->getId();
            $id = (int)$request->getPost('articleId');

            $article = $this->entityManager->getRepository(Article::class)->find($id);

            $articleLikeArray = $this->entityManager
                                     ->getRepository(ArticleLike::class)
                                     ->findBy(['article' => $id, 'user' => $userId]);

            if (!$articleLikeArray) {
                $articleDislikeArray = $this->entityManager
                                            ->getRepository(ArticleDislike::class)
                                            ->findBy(['article' => $id, 'user' => $userId]);

                if ($articleDislikeArray) {
                    $articleDislike = (int)$article->getArticleDislike();
                    $articleDislike--;
                    $article->setArticleDislike($articleDislike);
                    $this->entityManager->remove($articleDislikeArray[0]);
                }

                $articleLikeCount = (int)$article->getArticleLike();
                $articleLikeCount++;
                $article->setArticleLike($articleLikeCount);

                $articleLike = new ArticleLike();
                $articleLike->setArticle($id);
                $articleLike->setUser($userId);

                $this->entityManager->persist($article);
                $this->entityManager->persist($articleLike);
                $this->entityManager->flush();

                $articleLikeNew = $article->getArticleLike();
                $articleDislikeNew = $article->getArticleDislike();

                $response->setContent(\Zend\Json\Json::encode([
                    'success' => 1,
                    'articleLikeNew' => $articleLikeNew,
                    'articleDislikeNew' => $articleDislikeNew,
                ]));
            } else {
                $response->setContent(\Zend\Json\Json::encode('error'));
            }
        }

        return $response;
    }

    public function addDislikeAction()
    {
        $response = $this->getResponse();
        $request  = $this->getRequest();

        if ($request->isPost()) {
            $userId = (int)$this->identity()->getId();
            $id = (int)$request->getPost('articleId');
            $article = $this->entityManager->getRepository(Article::class)->find($id);

            $articleDislikeArray = $this->entityManager
                                        ->getRepository(ArticleDislike::class)
                                        ->findBy(['article' => $id, 'user' => $userId]);

            if (! $articleDislikeArray) {
                $articleLikeArray = $this->entityManager
                                         ->getRepository(ArticleLike::class)
                                         ->findBy(['article' => $id, 'user' => $userId]);

                if ($articleLikeArray) {
                    $articleLikeCount = (int)$article->getArticleLike();
                    $articleLikeCount--;
                    $article->setArticleLike($articleLikeCount);
                    $this->entityManager->remove($articleLikeArray[0]);
                }

                $articleDislike = (int)$article->getArticleDislike();
                $articleDislike++;
                $article->setArticleDislike($articleDislike);

                $articleDislike = new ArticleDislike();
                $articleDislike->setArticle($id);
                $articleDislike->setUser($userId);

                $this->entityManager->persist($article);
                $this->entityManager->persist($articleDislike);
                $this->entityManager->flush();

                $articleDislikeNew = $article->getArticleDislike();
                $articleLikeNew = $article->getArticleLike();

                $response->setContent(\Zend\Json\Json::encode([
                    'success' => 1,
                    'articleDislikeNew' => $articleDislikeNew,
                    'articleLikeNew' => $articleLikeNew,
                ]));
            } else {
                $response->setContent(\Zend\Json\Json::encode('error'));
            }
        }

        return $response;
    }

    public function addCommentAction()
    {
        $errors  = [];
        $commentText = '';

        $comment = new Comment();
        $form = $this->formService->getAnnotationForm($this->entityManager, $comment);

        $request  = $this->getRequest();
        $response = $this->getResponse();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $comment->setComment($this->textFilter($comment->getComment()));
                $commentText = $comment->getComment();

                $this->entityManager->persist($comment);
                $this->entityManager->flush();
            } else {
                $messages = $form->getMessages();
                if ($messages) {
                    foreach ($messages as $key => $message) {
                        if (! empty($key)) {
                            foreach ($message as $error) {
                                $errors[] = $error;
                            }
                        }
                    }
                }
            }

            $response->setContent(\Zend\Json\Json::encode(['errors' => $errors, 'commentText' => $commentText]));
            return $response;
        }
    }

    public function searchAction()
    {
        $articles = false;
        $request  = $this->getRequest();
        $response = $this->getResponse();
        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $search = $request->getPost('app-search-article');

        $stripTagsFilter = new \Zend\Filter\StripTags();
        $search = $stripTagsFilter->filter($search);

        $stringTrimFilter = new \Zend\Filter\StringTrim();
        $search = $stringTrimFilter->filter($search);

        if (! empty($search)) {
            $articles = $this->entityManager->getRepository(Article::class)->searchArticle($search);
        }

        $response->setContent(\Zend\Json\Json::encode($articles));
        return $response;
    }
}
