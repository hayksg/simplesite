<?php

namespace Authentication\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;

class LoginFilter extends InputFilter
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
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'password',
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
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'rememberMe',
            'required' => false,
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => [0, 1],
                    ],
                ],
            ],
        ]);
    }
}
