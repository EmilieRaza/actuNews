<?php

namespace App\Form;

use App\Entity\Article;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Titre de votre article'
            ])
            ->add('subtitle',TextType::class, [
                'label' => 'Sous titre de votre article'
            ])
            ->add('content',TextareaType::class, [
                'attr' => [
                    'placeholder' => 'ici le contenu de votre article'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir une categorie",
                'multiple' => false,  
                'expanded' => false
                
            ])
            ->add('photo',FileType::class,[
                'data_class' => null,
                'label' => 'Photo',
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les types de fichiers autorisés sont: .jpeg et .png',

                    ])
                 ],

                'attr'=> [
                    'data-default-file'=>$options['photo']
                ]
            ])
            // ->add('submit',SubmitType::class, [
            //     'label' => 'Créer votre article',
            //     'attr'=> [
            //         'class'=>'d-block col-3 my-3 mx-auto btn btn-dark w-100'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
