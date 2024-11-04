<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Routing\RouterInterface;

class LoginFormAuth extends AbstractLoginFormAuthenticator
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', ''))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        $roles = $token->getRoleNames();

        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif (in_array('ROLE_ARTIST', $roles)) {
            return new RedirectResponse($this->router->generate('artist_dashboard'));
        } elseif (in_array('ROLE_PRODUCTEUR', $roles)) {
            return new RedirectResponse($this->router->generate('producteur_dashboard'));
        } elseif (in_array('ROLE_BEATMAKER', $roles)) {
            return new RedirectResponse($this->router->generate('artist_dashboard'));
        } elseif (in_array('ROLE_CHANTEUR', $roles)) {
            return new RedirectResponse($this->router->generate('chanteur_dashboard'));
        }

        // Redirection par défaut
        return new RedirectResponse($this->router->generate('homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}
