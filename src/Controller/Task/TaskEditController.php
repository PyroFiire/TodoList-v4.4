<?php

namespace App\Controller\Task;

use App\Entity\Task;
use Twig\Environment;
use App\Form\Task\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskEditController
{
    private $twig;
    private $form;
    private $manager;
    private $router;
    private $taskRepository;
    
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $router,
        TaskRepository $taskRepository
    )
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->manager = $manager;
        $this->router = $router;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function TaskEdit($id, Request $request)
    {
        $task = $this->taskRepository->findOneById($id);
        $form = $this->form->create(TaskType::class, $task);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->manager->persist($task);
            $this->manager->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'La tâche a bien été modifiée.'
            );

            return new RedirectResponse($this->router->generate('task_list'));
        }

        return new Response($this->twig->render(
            'task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]));
    }
}
