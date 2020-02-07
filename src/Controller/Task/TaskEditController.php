<?php

namespace App\Controller\Task;

use App\Entity\Task;
use Twig\Environment;
use App\Form\Task\TaskType;
use App\Security\TaskVoter;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskEditController
{
    private $twig;
    private $form;
    private $manager;
    private $router;
    private $taskRepository;
    private $security;
    private $voter;
    
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $router,
        TaskRepository $taskRepository,
        Security $security,
        TaskVoter $voter
    )
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->manager = $manager;
        $this->router = $router;
        $this->taskRepository = $taskRepository;
        $this->security = $security;
        $this->voter = $voter;
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function TaskEdit($id, Request $request)
    {
        $task = $this->taskRepository->findOneById($id);

        $vote = $this->voter->vote($this->security->getToken(), $task, ['edit']);
        if ($vote < 1) {
            throw new AccessDeniedException();
        }

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
