<?php

namespace App\Form;

use App\Entity\Folder;
use App\Entity\Priority;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('title')
            ->add('Folder', EntityType::class, [
                'class' => Folder::class,
                'choice_label' => 'name',
                'placeholder' => 'Aucune dossier',
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('f')
                        ->where('f.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('f.name', 'ASC');
                }
            ])
            ->add('priority', EntityType::class, [
                'class' => Priority::class,
                'choice_label' => 'name',
                //query builder sirve para hacer una subconsulta
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->orWhere('p.user IS NULL')
                        ->setParameter('user', $user)
                        ->orderBy('p.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'user' => null,
        ]);
    }
}
