<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La biographie est obligatoire.']),
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La biographie ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('age', TextType::class, [
                'label' => 'Âge',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 3,
                        'maxMessage' => 'L\'âge doit être exprimé en moins de {{ limit }} chiffres.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'L\'âge doit être un nombre entier.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville est obligatoire.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom de la ville ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'csrf_protection' => true, // Active la protection CSRF
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'artist_item',
        ]);
    }
}
