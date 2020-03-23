<?php

namespace App\Controller\Task;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TaskListController
{
    private $twig;
    private $taskRepository;

    public function __construct(
        Environment $twig,
        TaskRepository $taskRepository
    ) {
        $this->twig = $twig;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function taskList(Request $request)
    {
        $tasksAreDone = $request->query->get('are-done');
        // dd($tasksAreDone);
        if ('' == $tasksAreDone) {
            $tasks = $this->taskRepository->findAll();
        }
        if ('false' === $tasksAreDone) {
            // dd('false');
            $tasks = $this->taskRepository->findBy(['isDone' => '0']);
        }
        if ('true' === $tasksAreDone) {
            $tasks = $this->taskRepository->findBy(['isDone' => '1']);
        }

        // $tasks = $this->taskRepository->findAll();

        return new Response($this->twig->render(
            'task/list.html.twig', [
            'tasks' => $tasks,
        ]));
    }
}
