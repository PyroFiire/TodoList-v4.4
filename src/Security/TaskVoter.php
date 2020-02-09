<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['edit', 'delete'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ROLE_ADMIN can do anything !
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'edit':
                return $this->canEdit($subject, $user);
                break;
            case 'delete':
                return $this->canDelete($subject, $user);
                break;
        }
        return false;
    }

    // logic, return true or false

    private function canEdit(Task $subject, User $user)
    {
        //return false if Anonyme Task
        if ($subject->getAuthor() !== null) {
            // return true if User created this Task
            return $user->getId() === $subject->getAuthor()->getId();
        }
        return false;
    }
    
    private function canDelete(Task $subject, User $user)
    {   
        // If he can edit, he can delete
        if ($this->canEdit($subject, $user)) {
             return true;
        }
    }
}
