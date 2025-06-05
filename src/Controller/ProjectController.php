<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Employee;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/project', name: 'app_', methods: ['GET', 'POST'])]
final class ProjectController extends AbstractController
{
    #[Route('/new', name: 'project_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function projectEdition(?Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $project ??= new Project; //Si aucun projet passé = Nouveau, si non edition
            $form = $this->createForm(ProjectType::class, $project);
            $form->get('archived')->setData(false);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($project);
                $entityManager->flush();

                return $this->redirectToRoute('app_project', [
                    'id' => $project->getId()
                ]);
            }

            return $this->render('project/projectForm.html.twig', [
                'form' => $form,
                'project' => $project
            ]);
        }
        return $this->redirectToRoute('app_project_index'); // redirige l'utilisateur s'il n'a pas accès
    }

    #[Route('/{id}/archive', name: 'project_archive', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function projectArchive(Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $project->setArchived(true);
            $entityManager->persist($project);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_project_index');
    }

    #[Route('/{id}', name: 'project', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function projectDetail(Project $project): Response
    {
        $this->denyAccessUnlessGranted('project.is_member', $project);
        return $this->render('project/project.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/index', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $projectRepository->findAll();
        } else {
            /** @var Employee|null $user */ //Php Doc pour forcer la reconnaiance de l'IDE comme une instance de Employee
            $user = $this->getUser();
            $projects = $user->getProjects();
        }
        return $this->render('project/index.html.twig', [
            'projects' => $projects
        ]);
    }
}
