<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez entrer une adresse email']),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse email valide']),
                ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudonyme',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le pseudonyme est obligatoire']),
                    new Assert\Length(['max' => 50]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 20,
                        'maxMessage' => 'Le numéro de téléphone ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire']),
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        ]),
                    ],
                    'attr' => ['placeholder' => 'Mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => 'Confirmer le mot de passe'],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'constraints' => [
                    new Assert\Image(['maxSize' => '2M']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Choisissez vos rôles',
                'choices' => [
                    'Musicien' => 'ROLE_MUSICIAN',
                    'Beatmaker' => 'ROLE_BEATMAKER',
                    'Producteur' => 'ROLE_PRODUCTEUR',
                    'Artiste' => 'ROLE_ARTIST',
                    'Chanteur' => 'ROLE_CHANTEUR',
                ],
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['ROLE_MUSICIAN', 'ROLE_BEATMAKER', 'ROLE_PRODUCTEUR', 'ROLE_ARTIST', 'ROLE_CHANTEUR'],
                        'multiple' => true,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
