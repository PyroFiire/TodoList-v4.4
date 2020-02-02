<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private $twig;

    public function __construct(
        Environment $twig
    )
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return new Response($this->twig->render(
            'default/index.html.twig', [] ));
    }
}
