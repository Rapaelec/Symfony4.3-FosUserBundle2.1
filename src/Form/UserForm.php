<?php
// src/AppBundle/Form/RegistrationType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserForm extends AbstractType
{
    private $encoder;
    public function __construct(
        UserPasswordEncoderInterface $userPassWordEncoderInterface
    )
    {
        $this->encoder = $userPassWordEncoderInterface;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
        ->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmit']
        )
        ;
    }

    public function onSubmit(FormEvent $event)
    {
        // getUser
        $user = $event->getData();
        //encode password
        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}