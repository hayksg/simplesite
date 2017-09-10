<?php

namespace Admin\Filter;

use Zend\InputFilter\InputFilter;

class CategoryFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 3,
                        'max' => 100,
                    ]
                ],
                ['name' => 'digits'],
            ],
        ]);
    }
}
