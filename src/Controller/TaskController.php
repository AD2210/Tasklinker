<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{
    #[Route('/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    #[Route('/task/{id}/edit', name: 'app_task_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function projectEdition(?Task $task, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task ??= new Task; //Si aucun projet passer = Nouveau si non edition
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project',[
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
