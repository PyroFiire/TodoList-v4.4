<?php

namespace App\Controller\User;

use App\Entity\User;
use Twig\Environment;
use App\Form\User\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEditController
{
    private $twig;
    private $form;
    private $passwordEncoder;
    private $manager;
    private $router;
    private $userRepository;
    
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $router,
        UserRepository $userRepository
    )
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        $this->router = $router;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function UserEdit($id, Request $request)
    {
        $user = $this->userRepository->findOneById($id);
        $form = $this->form->create(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $this->manager->persist($user);
            $this->manager->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'utilisateur a bien été modifié'
            );

            return new RedirectResponse($this->router->generate('user_list'));
        }

        return new Response($this->twig->render(
            'user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]));
    }
}
