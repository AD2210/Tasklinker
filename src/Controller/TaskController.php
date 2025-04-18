<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{
    #[Route(
        'project/{project_id}/task/new',
        name: 'app_task_new',
        requirements: ['project_id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    #[Route(
        'project/{project_id}/task/{task_id}/edit',
        name: 'app_task_edit',
        requirements: ['project_id' => '\d+', 'task_id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function taskEdition(?Task $task, Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($project);
        $task ??= new Task; //Si aucun projet passer = Nouveau si non edition
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project', [
                'id' => $task->getProject()->getId()
            ]);
        }

        return $this->render('task/taskForm.html.twig', [
            'controller_name' => 'ProjectController',
            'form' => $form,
            'task' => $task
        ]);
    }
}
