<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Validator\Constraint\Email;
use App\Validator\Constraint\NotBlank;
use App\Validator\Constraint\PasswordLength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank('Username should not be blank')
                ]
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank('Email should not be blank'),
                    new Email('Invalid Email')
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank('Password should not be blank'),
                    new PasswordLength(['min' => 6], 'Minimum password length is 6 symbols'),
                ],
                'property_path' => 'plainPassword'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => User::class,
        ]);
    }
}