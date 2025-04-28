<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{
    #[Route('/project/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[Route('/project/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function projectEdition(?Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        $project ??= new Project; //Si aucun projet passé = Nouveau, si non edition
        $form = $this->createForm(ProjectType::class, $project);
        $form->get('archived')->setData(false);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project',[
                'id' => $project->getId()
            ]);
        }

        return $this->render('project/projectForm.html.twig', [
            'controller_name' => 'ProjectController',
            'form' => $form,
            'project' => $project
        ]);
    }

    #[Route('/project/{id}/archive', name: 'app_project_archive', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function projectArchive(Project $project, EntityManagerInterface $entityManager): Response
    {
        $project->setArchived(true);         
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->redirectToRoute('app_main',[]);

    }

    #[Route('/project/{id}', name: 'app_project', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function projectDetail(Project $project): Response
    {
        return $this->render('project/project.html.twig', [
            'controller_name' => 'ProjectController',
            'project' => $project
        ]);
    }

    #[Route('/', name: 'app_main', methods:['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'MainController',
            'projects' => $projects
        ]);
    }
}
