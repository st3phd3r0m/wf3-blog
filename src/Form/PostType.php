<?php

namespace App\Form;

use App\Entity\Posts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez donner un titre !'
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 50,
                        'minMessage' => "Le titre doit comporter au minimum {{ limit }}
                        caractères !",
                        'maxMessage' => "Le titre doit comporter maximum {{ limit }}
                        caractères !"
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez entrer un texte !'
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères !",
                    ])
                ]
            ])
            // ->add('categorie', TextType::class, [
            //     'required' => false,
            //     'attr'=>[
            //         'class'=>'form-control'
            //     ],
            //     'constraints' => [
            //         new NotBlank([
            //             'message'=>'Veuillez donner un titre !'
            //         ]),
            //         new Length([
            //             'min' => 2,
            //             'max' => 20,
            //             'minMessage' => "La categorie doit comporter au minimum {{ limit }}
            //             caractères !",
            //             'maxMessage' => "La categorie doit comporter maximum {{ limit }}
            //             caractères !"
            //         ])
            //     ]
            // ])
            // ->add('image')
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
