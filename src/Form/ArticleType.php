<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' ,null,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            ])
            ->add('shortDescription',null,[
                'attr' => ['class' => 'form-control summernote'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            ])
            ->add('content',null,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            ])
           // ->add('createdAt')
            //->add('updatedAt')
            ->add('category',null,[
                'attr' => ['class' => 'form-control'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            ])
           // ->add('author')
            ->add('image', FileType::class, [
                
            'label' => 'image_simple',
            'multiple' => false,
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'form-control'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            
        ])
        ->add('imageMultiple', FileType::class,[
            'label' => "images",
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'form-control'],
                'label_attr' => [
                    'class' => 'form-label',
                    'style' => 'font-weight: bold; color: red;',
                ],
            
        ])
    

        
    ;
           
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            
        ]);
    }
}
