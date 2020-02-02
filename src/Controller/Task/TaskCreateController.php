<?php

namespace App\Controller\Task;

use App\Entity\Task;
use Twig\Environment;
use App\Form\Task\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskCreateController
{
    private $twig;
    private $form;
    private $manager;
    private $router;
    
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $router
    )
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->manager = $manager;
        $this->router = $router;
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function TaskCreate(Request $request)
    {
        $form = $this->form->create(TaskType::class, $task = new Task());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->manager->persist($task);
            $this->manager->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'La tâche a été bien été ajoutée.'
            );

            return new RedirectResponse($this->router->generate(
                'task_list'
            ));
        }

        return new Response($this->twig->render(
            'task/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
