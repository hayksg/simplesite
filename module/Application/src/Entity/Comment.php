<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="article_id_index", columns={"article_id"})})
 * @ORM\Entity
 *
 * @Annotation\Name("comment")
 */
class Comment
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
     * @ORM\Column(name="comment", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"userComment", "required":"required"})
     * @Annotation\Options({"min":"2"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2"}})
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $registrationDate;

    /**
     * @var \Application\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=true)
     * })
     *
     * @Annotation\Type("Zend\Form\Element\Hidden")
     */
    private $article;
	
	/**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
	 *
	 * @Annotation\Type("Zend\Form\Element\Hidden")
     */
    private $user;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Send"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;

    public function __construct()
    {
        $this->registrationDate = new \DateTime();
    }


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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     *
     * @return Comment
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set article
     *
     * @param \Application\Entity\Article $article
     *
     * @return Comment
     */
    public function setArticle(\Application\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Application\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
	
	/**
     * Set user
     *
     * @param \Application\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
	
	/**
     * Get user
     *
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

