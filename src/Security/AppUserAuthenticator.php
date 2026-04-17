<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppUserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Esto es por si entra en algun lugar donde se necesita logguearse
        // 1. ¿El usuario quería ir a una página específica antes?
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // AQUÍ es donde ponés tu ruta por defecto si el usuario entró directamente al login
        // 2. Si no, lo mando a la lista de tareas.
        return new RedirectResponse($this->urlGenerator->generate('app_task_index'));

        // Esta línea de abajo (el throw Exception) TENÉS QUE BORRARLA o comentarla
        //Por que?
        // 3. Esta línea ya no tiene sentido que exista.
        // Si llegara acá, la aplicación "explota" por seguridad para avisarte que te 
        //olvidaste de configurar el paso 2
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__)
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
