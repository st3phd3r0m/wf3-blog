<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label'=>'Titre de l\'article',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir un titre.',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => "Le titre doit comporter au minimum {{ limit }}
                        caractères !",
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('content', CKEditorType::class, [
                'config_name'=> 'my_config_1',    
                'required' => true,
                'label'=>'Contenu d\'article',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez entrer votre contenu',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères !",
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('created_at', DateType::class, [
                'required' => true,
                'label'=>'Date de publication',
                // 'data'=> new \DateTime(),  //permet de forcer le remplissage de l'input
                'widget'=> 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir une date',
                        'groups'=> ['new', 'update']
                    ]),
                    new DateTime([
                        'message'=> 'La date est invalide',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('categorie', EntityType::class, [
                'label'=>'Choisir une catégorie',
                'class'=> Categories::class,
                'choice_label'=> 'name'
            ])
            ->add('imageFile', VichImageType::class,[
                'required' => true,
                'label'=>'Image de présentation',
                'download_link'=>false,
                'imagine_pattern'=>'miniatures',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez choisir une image de présentation',
                        'groups'=> ['new']
                    ]),
                    new Image([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=> 'Votre image dépasse les 2Mo',
                        'mimeTypes'=>['image/png', 'image/gif', 'image/jpeg'],
                        'mimeTypesMessage'=>'Votre image doit être de type PNG, GIF ou JPEG',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('poster', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
