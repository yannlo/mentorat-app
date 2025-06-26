<?php

namespace App\Middleware;

use App\Entity\Users\Mentor\Mentor;
use App\Entity\Enums\MentorRegisterStep;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MentorRegisterStepMiddleware implements EventSubscriberInterface
{
    private Security $security;
    private UrlGeneratorInterface $router;

    public function __construct(Security $security, UrlGeneratorInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // Vérifier uniquement sur la requête principale
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        
        if (null === $user) {
            return;
        }
        
        if (!$user instanceof Mentor) {
            return;
        }
        
        $routeName = $event->getRequest()->attributes->get('_route');
        if($routeName == "app_mentor_register"){
            return;
        }


        // Supposons que l'utilisateur possède une méthode getState() 
        // qui retourne son état. Par exemple "active" pour un utilisateur actif.
        if ($user->getRegisterStep() !== MentorRegisterStep::COMPLETED) {
            // Redirige luser->getRegisterStep()'utilisateur vers une page indiquant que son compte n'est pas actif.
            $url = $this->router->generate('app_mentor_register',[
                "step" => $user->getRegisterStep()->getNext()->value
            ]);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
