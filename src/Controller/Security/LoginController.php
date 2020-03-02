<?php

namespace App\Controller\Security;

use Twig\Environment;

use App\Form\Security\LoginType;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController
{

    /**
     * @var Environment
     */
    private $twig;
    
    /**
     * @var FormFactoryInterface
     */
    private $form;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;
    
    
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        AuthenticationUtils $authenticationUtils
    )
    {
        $this->twig = $twig;
        $this->form = $form;
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        $formLogin = $this->form->create(LoginType::class);

        return new Response($this->twig->render(
            'security/login.html.twig', [
            'formLogin' => $formLogin->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ]));
    }
}