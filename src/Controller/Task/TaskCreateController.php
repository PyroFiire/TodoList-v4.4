<?php

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\Task\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskCreateController extends AbstractController
{
    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function TaskCreate(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskType::class, $task = new Task());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
