<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;




class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],

                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
                
            ])
                
                
            
            //->add('roles')
            ->add('password',PasswordType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => [
                'class' => 'form-label',
                'style' => 'font-weight: bold; color: red;',
            ],
        ]);
    }

   


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
