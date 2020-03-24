<?php

namespace App\Controller\Security;

use App\Form\Security\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $formLogin = $this->createForm(LoginType::class);

        return $this->render('security/login.html.twig', [
            'formLogin' => $formLogin->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
