<?php

namespace App\Controller\Task;

use Twig\Environment;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        EntityManagerInterface $manager
    )
    {
        $this->twig = $twig;
        $this->taskRepository = $taskRepository;
        $this->router = $router;
        $this->manager = $manager;
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function TaskDelete($id, Request $request)
    {
        $task = $this->taskRepository->findOneById($id);
        
        $this->manager->remove($task);
        $this->manager->flush();

        $request->getSession()->getFlashBag()->add(
            'success',
            'La tâche a bien été supprimée.'
        );

        return new RedirectResponse($this->router->generate('task_list'));
    }
}