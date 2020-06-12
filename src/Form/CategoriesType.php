<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label'=>'Nom de la catégorie',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir un nom de catégorie.'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => "Le nom de la nouvelle catégorie doit comporter au moins {{ limit }}
                        caractères !"
                    ])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
