<?php

namespace Admin\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

class ArticleFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'title',
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
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'ingredient',
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
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'instruction',
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
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'isPublic',
            'filters' => [
                [
                    'name' => 'Boolean',
                    'options' => [
                        'allow_empty' => false,
                    ],
                ],
            ],
        ]);

        $this->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => false,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                ['name'    => 'FileIsImage'],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'            =>'./public_html/img/article/',
                        'useUploadName'     =>true,
                        'useUploadExtension'=>true,
                        'overwrite'         =>true,
                        'randomize'         =>false
                    ]
                ]
            ],
        ]);
    }
}
