<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class UserListController
{
    private $twig;
    private $userRepository;

    public function __construct(
        Environment $twig,
        UserRepository $userRepository
    ) {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function userList()
    {
        $users = $this->userRepository->findAll();

        return new Response($this->twig->render(
            'user/list.html.twig', [
            'users' => $users,
        ]));
    }
}
