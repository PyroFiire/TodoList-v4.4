<?php

namespace App\Controller\Task;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskListController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function taskList(Request $request, TaskRepository $taskRepository): Response
    {
        $tasksAreDone = $request->query->get('are-done');

        $tasks = [];
        if ('' == $tasksAreDone) {
            $tasks = $taskRepository->findAll();
        }
        if ('false' === $tasksAreDone) {
            $tasks = $taskRepository->findBy(['isDone' => '0']);
        }
        if ('true' === $tasksAreDone) {
            $tasks = $taskRepository->findBy(['isDone' => '1']);
        }

        return $this->render( 'task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
