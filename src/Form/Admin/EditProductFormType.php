<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Form\DTO\EditProductModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'    => 'Title (from class)',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control',
                ],

                'constraints' => [
                    new NotBlank([], 'Should be filled')
                ]

            ])
            ->add('price', NumberType::class, [
                'label'    => 'Price (from class)',
                'required' => true,
                'scale'    => 2,
                'html5'    => true,
                'attr'     => [
                    'step'  => '0.01',
                    'min'   => '0',
                    'class' => 'form-control',
                ]

            ])
            ->add('quantity', IntegerType::class, [
                'label'    => 'Quantity (from class)',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control',
                ]
            ])

            ->add('category', EntityType::class, [
                'label' => 'category',
            'required' => true,
            'class' =>Category::class,
            'choice_label' => 'title',
            'attr' => [
                'class' => 'form-control',
            ]
            ])

            ->add('description', TextareaType::class, [
                'label'    => 'Description (from class)',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control',
                    'style' => 'overflow:hidden;'
                ]
            ])


            ->add('newImage', FileType::class,[
                'label'=>'Choose new image',
                'required' => false,
                'attr'=> [
                    'class' => 'form-control-file'
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'label'      => 'Published',
                'required'   => false,
                'attr'       => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('isDeleted', CheckboxType::class, [
                'label'      => 'Deleted',
                'required'   => false,
                'attr'       => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditProductModel::class,
        ]);
    }
}
