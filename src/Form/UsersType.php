<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label'=>'Email de l\'utilisateur',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=> 'Veuillez saisir une adresse courriel.'
                    ]),
                    new Email([
                        'message'=> 'Veuillez saisir une adresse courriel valide.'
                    ])
                ]
            ])
            // ->add('roles', ChoiceType::class, [
            //     'required'=>true,
            //     'label'=>'Role de l\'utilisateur',
            //     'choices'=>[
            //         'Utilisateur'=> 'ROLE_USER',
            //         'Administrateur' => 'ROLE_ADMIN'
            //     ],
            //     'expanded' => true,
            //     'multiple' => true,
            //     'constraints'=>[
            //         new NotBlank([
            //             'message'=> 'Veuillez choisir un role d\'utilisateur.'
            //         ])
            //     ]
            // ])

            ->add('roles', CollectionType::class, [
                'entry_type'=>ChoiceType::class,
                'entry_options'=>[
                    'label'=>false,
                    'choices'=>[
                        'Utilisateur'=> 'ROLE_USER',
                        'Editeur'=>'ROLE_EDITOR',
                        'Administrateur' => 'ROLE_ADMIN'
                    ]
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez choisir un role d\'utilisateur.'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'required'=>true,
                'label'=>'Prénom',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez saisir le prénom de l\'utilisateur.'
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'required'=>true,
                'label'=>'Nom',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez saisir le nom de l\'utilisateur.'
                    ])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
