<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="parent_id_index", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CategoryRepository")
 *
 * @Annotation\Name("category")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class Category
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
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"class":"form-control", "id":"parentId"})
     * @Annotation\Options({
     *   "label":"Parent ID",
     *   "label_attributes":{"class":"control-label col-sm-3"},
     *   "empty_option": "Select Parent ID or will be created new category",
     *   "target_class":"Application\Entity\Category",
     *   "property": "name"
     * })
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"name", "required":"required"})
     * @Annotation\Options({"label":"Category name", "label_attributes":{"class":"control-label col-sm-3"}, "min":"2", "max":"100"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2", "max":"100"}})
     */
    private $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn primary", "value":"Submit"})
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Category
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return __CLASS__;
    }
}

