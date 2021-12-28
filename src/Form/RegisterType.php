<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut être vide. Veuillez saisir un email.'])
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre e-mail'
                ]
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe',
                'attr'=> [
                    'placeholder' => 'Choisissez un mot de passe fort'
                ],
                // verfication coté server
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut être vide. Veuillez saisir un mot de passse.'])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Choisissez un prénom'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Vous n avez pas de prénom']),
                    new Length([
                        'min' => 2,
                        'max' => 20,
                        'minMessage' => 'votre prénom est trop court, il doit comporter au minimum {{limit}} caractères'
                    ]),
                ]

            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Choisissez un nom'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Vous n\'avez pas de nom']),
                    new Length([
                        'min' => 2,
                        'max' => 20,
                        'minMessage' => 'votre nom est trop court, il doit comporter au minimum {{limit}} caractères'
                    ]),
                ]

            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr' => [
                    'class' => 'd-block col-3 mx-auto btn-dark'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
