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

final class EmployeeController extends AbstractController
{
    #[Route('/employee/{id}/edit', name: 'app_employee_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employeeEdition(Employee $employee, Request $request, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/employee/{id}/edit', name: 'app_employee_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employeeRemove(Employee $employee, Request $request, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/employee', name: 'app_employee')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/employees.html.twig', [
            'employees' => $employees
        ]);
    }
}
