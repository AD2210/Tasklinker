<?php

namespace App\Security\Voter;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectEditVoter extends Voter
{

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'project.is_member' === $attribute && $subject instanceof Project;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof Employee) {
            return false;
        }

        /** @var Project $subject */
        // On vérifie si l'utilisateur connecté est présent dans la liste des employés affectés au projet afin de lui donné accès
        foreach ($subject->getEmployees() as $employee) {
            if ($user === $employee) {
                return true;
            }
        }
        return false;
    }
}