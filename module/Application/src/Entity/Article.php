<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="category_id_index", columns={"category_id"}), @ORM\Index(name="article_like_index", columns={"article_like"}), @ORM\Index(name="article_dislike_index", columns={"article_dislike"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ArticleRepository")
 *
 * @Annotation\Name("article")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Annotation\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"title", "required":"required"})
     * @Annotation\Options({"label":"Title", "min":"2", "max":"100"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2", "max":"100"}})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"description"})
     * @Annotation\Options({"label":"Description"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8"}})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredient", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"ingredient", "required":"required"})
     * @Annotation\Options({"label":"Ingredient", "min":"2"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2"}})
     */
    private $ingredient;

    /**
     * @var string
     *
     * @ORM\Column(name="instruction", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"instruction", "required":"required"})
     * @Annotation\Options({"label":"Instruction", "min":"2"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2"}})
     */
    private $instruction;

    /**
     * @var \Application\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     * })
     *
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"class":"form-control", "id":"category", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Options({
     *   "label":"Categories",
     *   "empty_option": "Select category",
     *   "target_class":"Application\Entity\Category",
     *   "property": "name"
     * })
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"id":"isPublic"})
     * @Annotation\Options({"label":"Is Public", "set_hidden_element":"true", "checked_value":"1", "unchecked_value":"0"})
     * @Annotation\Filter({"name":"Boolean"})
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $isPublic = "0";

    /**
     * @var integer
     *
     * @ORM\Column(name="article_like", type="integer", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Exclude()
     */
    private $articleLike = "0";

    /**
     * @var integer
     *
     * @ORM\Column(name="article_dislike", type="integer", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Exclude()
     */
    private $articleDislike = "0";

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Name("file")
     * @Annotation\Attributes({"class":"jfilestyle", "id":"file"})
     * @Annotation\Options({"label":"Upload image"})
     * @Annotation\Validator({"name":"Zend\Validator\File\Extension", "options":{
     *     "extension":{"png", "jpeg", "jpg", "gif"}
     * }})
     * @Annotation\Validator({"name":"Zend\Validator\File\IsImage"})
     * @Annotation\Validator({"name":"Zend\Validator\File\Size", "options":{"max":"20000000"}})
     * @Annotation\Input("Zend\InputFilter\FileInput")
     * @Annotation\Filter({
     *     "name":"FileRenameUpload",
     *     "options":{
     *         "target":"./public_html/img/article/",
     *         "useUploadName":true,
     *         "useUploadExtension":true,
     *         "overwrite":true,
     *         "randomize":false
     *     }
     * })
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $image;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Submit"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ingredient
     *
     * @param string $ingredient
     *
     * @return Article
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return string
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set instruction
     *
     * @param string $instruction
     *
     * @return Article
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * Get instruction
     *
     * @return string
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     *
     * @return Article
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set articleLike
     *
     * @param integer $articleLike
     *
     * @return Article
     */
    public function setArticleLike($articleLike)
    {
        $this->articleLike = $articleLike;

        return $this;
    }

    /**
     * Get articleLike
     *
     * @return integer
     */
    public function getArticleLike()
    {
        return $this->articleLike;
    }

    /**
     * Set articleDislike
     *
     * @param integer $articleDislike
     *
     * @return Article
     */
    public function setArticleDislike($articleDislike)
    {
        $this->articleDislike = $articleDislike;

        return $this;
    }

    /**
     * Get articleDislike
     *
     * @return integer
     */
    public function getArticleDislike()
    {
        return $this->articleDislike;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param \Application\Entity\Category $category
     *
     * @return Article
     */
    public function setCategory(\Application\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Application\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}

