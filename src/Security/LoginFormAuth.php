<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuth extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

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
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirection vers la dernière page demandée par l'utilisateur s'il est connecté
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Récupère les rôles de l'utilisateur
        $roles = $token->getRoleNames();

        // Redirige en fonction des rôles de l'utilisateur
        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif (in_array('ROLE_ARTIST', $roles)) {
            return new RedirectResponse($this->router->generate('artist_dashboard'));
        } elseif (in_array('ROLE_PRODUCTEUR', $roles)) {
            return new RedirectResponse($this->router->generate('producteur_dashboard'));
        } elseif (in_array('ROLE_BEATMAKER', $roles)) {
            return new RedirectResponse($this->router->generate('beatmaker_dashboard'));
        }

        // Par défaut, redirige vers la page d'accueil si aucun rôle spécifique n'est trouvé
        return new RedirectResponse($this->router->generate('homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        // Spécifie la route de la page de connexion ici
        return $this->router->generate('app_login');
    }
}
