<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait HelperLoginTrait
{
    /**
     * Connecter un utilisateur sur le client en se basant sur le système de Cookie
     **/
    public function login (string $name): KernelBrowser
    {
        $client = static::createClient();
        $user = self::$container->get(UserRepository::class)->findOneByUsername($name);
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $client;
    }
}
