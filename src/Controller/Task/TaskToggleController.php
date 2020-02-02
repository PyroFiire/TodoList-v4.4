<?php

namespace App\Controller\Task;

use Twig\Environment;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskToggleController
{
    private $twig;
    private $taskRepository;
    private $router;
    private $manager;

    public function __construct(
        Environment $twig,
        TaskRepository $taskRepository,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager
    )
    {
        $this->twig = $twig;
        $this->taskRepository = $taskRepository;
        $this->router = $router;
        $this->manager = $manager;
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function taskToggle($id, Request $request)
    {
        $task = $this->taskRepository->findOneById($id);
        $task->toggle(!$task->isDone());
        $this->manager->flush();

        $request->getSession()->getFlashBag()->add(
            'success',
            sprintf(($task->isDone() == true) ? 'La tâche %s a bien été marquée comme faite.'
                                              : 'La tâche %s a bien été marquée comme non terminée.', $task->getTitle())
        );

        return new RedirectResponse($this->router->generate('task_list'));
    }
}