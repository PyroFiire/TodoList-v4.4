<?php

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskToggleController extends AbstractController
{
    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function taskToggle(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $task = $em->getRepository(Task::class)->findOneById($id);
        $task->toggle(!$task->isDone());
        $em->flush();
        $this->addFlash('success',
            sprintf((true == $task->isDone()) ? 'La tâche %s a bien été marquée comme faite.'
                                              : 'La tâche %s a bien été marquée comme non terminée.', $task->getTitle())
        );

        return $this->redirectToRoute('task_list');
    }
}
