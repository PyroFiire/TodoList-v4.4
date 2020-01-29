<?php

namespace App\Controller\User;

use Twig\Environment;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;

class UserListController
{
    private $twig;
    private $userRepository;

    public function __construct(
        Environment $twig,
        UserRepository $userRepository
    )
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        $users = $this->userRepository->findAll();
        return new Response($this->twig->render(
            'user/list.html.twig', [
            'users' => $users
        ]));
    }
}