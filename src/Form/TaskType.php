<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('deadline', DateType::class, [
                'label' => 'date',
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done'
                ]
            ])
            ->add('project', HiddenType::class, [
                'class' => Project::class,
                'choice_label' => 'id',
                'project_id' => $options['project_id']
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
