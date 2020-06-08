<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required'=>true,
                'label'=>'Prénom',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez taper votre prénom.'
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'required'=>true,
                'label'=>'Nom',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez taper votre nom.'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required'=>true,
                'label'=>'Courriel',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez taper votre courriel.'
                    ]),
                    new Email([
                        'message'=> 'Veuillez taper une adresse courriel valide.'
                    ])
                ]
            ])
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])         
            ->add('plainPassword', RepeatedType::class, [
                'required'=>true,
                'type'=>PasswordType::class,
                'invalid_message'=> 'Les mots de passe ne correspondent pas',
                'first_options'=>['label'=>'Mot de passe'],
                'second_options'=>['label'=>'Confirmation du mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ],
            ])    
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('S\'inscrire', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
