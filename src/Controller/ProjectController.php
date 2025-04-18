<?php

namespace App\Controller;

use App\Entity\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/project/{id}', name: 'app_project')]
    public function index(Project $project): Response
    {
        dump($project);

        return $this->render('project/project.html.twig', [
            'controller_name' => 'ProjectController',
            'project' => $project
        ]);
    }
}
