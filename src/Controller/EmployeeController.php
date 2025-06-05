<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/employee', name: 'app_')]
final class EmployeeController extends AbstractController
{
    #[Route('/{id}/edit', name: 'employee_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employeeEdition(Employee $employee, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(EmployeeType::class, $employee);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $entityManager->persist($employee);
                $entityManager->flush();

                return $this->redirectToRoute('app_employee');
            }

            return $this->render('employee/employeeForm.html.twig', [
                'form' => $form,
                'employee' => $employee
            ]);
        }
        return $this->redirectToRoute('app_project_index'); // redirige l'utilisateur s'il n'a pas accès
    }

    #[Route('/{id}/remove', name: 'employee_remove', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employeeRemove(Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            if (!$employee) {
                return $this->redirectToRoute('app_project_index');
            }

            $entityManager->remove($employee);
            $entityManager->flush();

            return $this->redirectToRoute('app_employee');
        }
        return $this->redirectToRoute('app_project_index'); // redirige l'utilisateur s'il n'a pas accès
    }

    #[Route('/', name: 'employee')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $employees = $employeeRepository->findAll();

            return $this->render('employee/employees.html.twig', [
                'employees' => $employees
            ]);
        }
        return $this->redirectToRoute('app_project_index'); // redirige l'utilisateur s'il n'a pas accès
    }
}
