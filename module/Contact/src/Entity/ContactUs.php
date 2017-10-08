<?php

namespace Contact\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("contactUs")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class ContactUs
{
    /**
     * @Annotation\Type("Zend\Form\Element\Csrf")
     * @Annotation\Name("csrf")
     * @Annotation\Options({
     *     "csrf_options":{
     *          "timeout":600
     *     }
     * })
     */
    private $csrf;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"name", "required":"required"})
     * @Annotation\Options({"label":"Name", "label_attributes":{"class":"control-label col-sm-3"}, "min":"1", "max":"50"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"1", "max":"50"}})
     */
    private $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Attributes({"class":"form-control", "id":"email", "required":"required"})
     * @Annotation\Options({"label":"Email", "label_attributes":{"class":"control-label col-sm-3"}})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"stringTrim"})
     * @Annotation\Validator({"name":"emailAddress"})
     */
    private $email;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"message", "required":"required"})
     * @Annotation\Options({"label":"Message", "label_attributes":{"class":"control-label col-sm-3"}, "min":"2", "max":"500"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Filter({"name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2", "max":"500"}})
     */
    private $message;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Submit"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;
}
