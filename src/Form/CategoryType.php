<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Category',
                'attr' => [
                    'placeholder' => 'Select or write a new one...'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description (not required)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Describe it shortly'
                ]
            ])
            ->add('Done', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-sm float-right'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
