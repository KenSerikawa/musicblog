<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\CategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true, 
                'label' => 'Track name'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Track description',
                'attr' => [
                    'rows' => 2
                ]
            ])
            ->add('category', CategoryType::class, [
                'label' => '  ',
                'attr' => [
                    'placeholder' => 'Select category or write a new one'
                ]
            ])
            ->add('trackname', FileType::class, [
                'label' => 'Audio file'
                
            ])
            ->add('imagename', FileType::class, [
                'required' => false,
                'label' => 'Image (not required)',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
