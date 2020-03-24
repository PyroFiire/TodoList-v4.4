<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    /**
     * @var Security
     */
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
        if (!$user instanceof User) {
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
            case 'delete':
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    // logic, return true or false

    private function canEdit(Task $subject, User $user): bool
    {
        //return false if Anonyme Task
        if (null !== $subject->getAuthor()) {
            // return true if User created this Task
            return $user->getId() === $subject->getAuthor()->getId();
        }

        return false;
    }

    private function canDelete(Task $subject, User $user): bool
    {
        // If he can edit, he can delete
        if ($this->canEdit($subject, $user)) {
            return true;
        }

        return false;
    }
}
