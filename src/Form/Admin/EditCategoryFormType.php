<?php

namespace App\Form\Admin;


use App\Form\DTO\EditCategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditCategoryFormType extends AbstractType
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

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditCategoryModel::class,
        ]);
    }
}
