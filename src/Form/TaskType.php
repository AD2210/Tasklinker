<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
                'required' => true
                ])
            ->add('deadline', DateType::class, [
                'required' => false,
                'label' => 'date',
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
                ])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'label' => 'Statut',
                'choices' => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done'
                ]
            ])
            ->add('employee', EntityType::class, [
                'required' => false,
                'label' => 'Membre',
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $employeeRepository) use ($options) {
                    $project = $options['project'];

                    return $employeeRepository->createQueryBuilder('e')
                        ->innerJoin('e.projects', 'p')
                        ->andWhere('p = :proj')
                        ->setParameter('proj', $project);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'project'    => null,
        ]);

        // on exige que 'project' soit un Project ou null
        $resolver->setAllowedTypes('project', [Project::class, 'null']);
    }
}
