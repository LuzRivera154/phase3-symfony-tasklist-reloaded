<?php

namespace App\Form;

use App\Entity\Folder;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'color',
                ChoiceType::class,
                [
                    'choices' => [
                        'Red' => '#f87171',
                        'Orange' => '#fb923c',
                        'Amber' => '#fbbf24',
                        'Yellow' => '#facc15',
                        'Lime' => '#a3e635',
                        'Green' => '#4ade80',
                        'Emerald' => '#34d399',
                        'Teal' => '#2dd4bf',
                        'Cyan' => '#22d3ee',
                        'Sky' => '#38bdf8',
                        'Blue' => '#60a5fa',
                        'Indigo' => '#818cf8',
                        'Violet' => '#a78bfa',
                        'Purple' => '#c084fc',
                        'Fuchsia' => '#e879f9',
                        'Pink' => '#f472b6',
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Couleur'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Folder::class,
        ]);
    }
}
