<?php

namespace App\Form;

use App\Entity\Musicien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('instrument', TextType::class, [
                'label' => 'Instrument',
            ])
            ->add('genre_musical', TextType::class, [
                'label' => 'Genre Musical',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'mapped' => false, // Ne pas mapper directement pour gérer l’upload
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musicien::class,
        ]);
    }
}
