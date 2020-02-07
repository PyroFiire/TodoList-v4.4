<?php

namespace App\Controller\Task;

use Twig\Environment;
use App\Security\TaskVoter;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskDeleteController
{
    private $twig;
    private $taskRepository;
    private $router;
    private $manager;

    public function __construct(
        Environment $twig,
        TaskRepository $taskRepository,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        Security $security,
        TaskVoter $voter
    )
    {
        $this->twig = $twig;
        $this->taskRepository = $taskRepository;
        $this->router = $router;
        $this->manager = $manager;
        $this->security = $security;
        $this->voter = $voter;
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function TaskDelete($id, Request $request)
    {
        $task = $this->taskRepository->findOneById($id);
        
        $vote = $this->voter->vote($this->security->getToken(), $task, ['delete']);
        if ($vote < 1) {
            throw new AccessDeniedException();
        }
        
        $this->manager->remove($task);
        $this->manager->flush();

        $request->getSession()->getFlashBag()->add(
            'success',
            'La tâche a bien été supprimée.'
        );

        return new RedirectResponse($this->router->generate('task_list'));
    }
}