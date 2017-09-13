<?php

namespace Authentication\Form;

use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Csrf;
use Zend\Captcha\Image as CaptchaImage;

class RegisterForm extends Form
{
    public function __construct($urlCaptcha = null)
    {
        parent::__construct('register-form');

        $this->setAttributes([
            'class' => 'form-horizontal',
            'id' => 'form-register',
        ]);

        $this->createElements($urlCaptcha);
    }

    private function getCaptchaImage($urlCaptcha)
    {
        $dirData = './data';

        $captchaImage = new CaptchaImage([
            'font' => $dirData . '/fonts/arial.ttf',
            'wordlen' => 7,
            'width'   => 200,
            'height'  => 50,
            'dotNoiseLevel'  => 30,
            'lineNoiseLevel' => 3,
        ]);

        $captchaImage->setImgDir($dirData . '/captcha');
        $captchaImage->setImgUrl($urlCaptcha);

        return $captchaImage;
    }

    private function createElements($urlCaptcha)
    {
        $this->add([
            'name' => 'csrf',
            'type' => Csrf::class,
            'options' => [
                'crsf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class'    => 'form-control',
                'id'       => 'name',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Username',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'min' => 2,
                'max' => 100,
            ],
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'email',
            'attributes' => [
                'class'    => 'form-control',
                'id'       => 'email',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Email',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'min' => 2,
                'max' => 100,
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'attributes' => [
                'class'    => 'form-control',
                'id'       => 'password',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Password',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'min' => 2,
                'max' => 100,
            ],
        ]);

        $this->add([
            'name' => 'confirmPassword',
            'type' => 'password',
            'attributes' => [
                'class'    => 'form-control',
                'id'       => 'confirmPassword',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Confirm Password',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'min' => 2,
                'max' => 100,
            ],
        ]);

        $this->add([
            'name' => 'captcha',
            'type' => Captcha::class,
            'options' => [
                'label' => 'Type the word',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'captcha' => $this->getCaptchaImage($urlCaptcha),
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'class' => 'btn btn-default',
                'value' => 'Submit',
            ],
        ]);
    }
}
