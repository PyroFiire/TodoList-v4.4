<?php

namespace App\Controller\Task;

use Twig\Environment;
use App\Entity\Task;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TaskRepository;

class ListActionController
{
    private $twig;
    private $taskRepository;

    public function __construct(
        Environment $twig,
        TaskRepository $taskRepository
    )
    {
        $this->twig = $twig;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        /* 3.1 code
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
        */

        $tasks = $this->taskRepository->findAll();
        return new Response($this->twig->render(
            'task/list.html.twig', [
            'task' => $tasks
        ]));
    }
}