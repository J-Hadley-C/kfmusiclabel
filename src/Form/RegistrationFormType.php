<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'artiste',
                'mapped' => false, // Indique qu'il n'est pas mappé à `User`
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer le nom de l\'artiste.',
                    ]),
                ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudonyme',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un pseudonyme.',
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer votre numéro de téléphone.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\+?\d{9,15}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide (9 à 15 chiffres, avec un + optionnel).',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        'max' => 20,
                        'maxMessage' => 'Votre mot de passe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'mapped' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Musicien' => 'ROLE_ARTIST',
                    'Producteur' => 'ROLE_PRODUCTEUR',
                    'Beatmaker' => 'ROLE_BEATMAKER',
                    'Chanteur' => 'ROLE_CHANTEUR',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
