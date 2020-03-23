<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class UserCreateController
{
    private $twig;
    private $form;
    private $passwordEncoder;
    private $manager;
    private $router;

    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $router
    ) {
        $this->twig = $twig;
        $this->form = $form;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        $this->router = $router;
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function UserCreate(Request $request)
    {
        $form = $this->form->create(UserType::class, $user = new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $this->manager->persist($user);
            $this->manager->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'utilisateur a bien été ajouté.'
            );

            return new RedirectResponse($this->router->generate(
                'homepage' //user_list
            ));
        }

        return new Response($this->twig->render(
            'user/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
