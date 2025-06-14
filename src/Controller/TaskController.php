<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('project/{project_id}/task',name: 'app_task_')]
final class TaskController extends AbstractController
{
    #[Route(
        '/new/{task_status}',
        name: 'new',
        requirements: ['project_id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    #[Route(
        '/{task_id}/edit',
        name: 'edit',
        requirements: ['project_id' => '\d+', 'task_id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function taskEdition(
        #[MapEntity(id: 'task_id')]
        ?Task $task,
        ?string $task_status,
        #[MapEntity(id: 'project_id')]
        Project $project,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        
        if ($this->isGranted('ROLE_ADMIN')){

            $task ??= new Task; //Si aucune tâche passée = Nouvelle, si non edition
            
            // On set les valeurs par défaut dans le formulaire : project et status si nouvelle tâche
            $task->setProject($project);
            if ( !$task_status == null){
            $task->setStatus($task_status); 
            }

            $form = $this->createForm(TaskType::class, $task, [
                // on passe le project en parametre du formulaire pour selectionner uniquement les employés affecté au project
                'project' => $project,
            ]);
            

            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('app_project', [
                    'id' => $project->getId()
                ]);
            }

            return $this->render('task/taskForm.html.twig', [
                'form' => $form,
                'task' => $task,
                'project' => $project
            ]);
        }
        return $this->redirectToRoute('app_project', [
            'id' => $project->getId()
        ]);
    }

    #[Route(
        '/{task_id}/remove',
        name: 'remove',
        requirements: ['project_id' => '\d+', 'task_id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function taskRemove(
        #[MapEntity(id: 'task_id')]
        Task $task,
        #[MapEntity(id: 'project_id')]
        Project $project,
        EntityManagerInterface $entityManager,
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null,'Access Denied.');
        if (!$task) {
            return $this->redirectToRoute('app_project_index');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_project', [
            'id' => $project->getId()
        ]);
    }
}
