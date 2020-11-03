<?php

namespace Drupal\hello_world\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\LocalRedirectResponse;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;

/**
 * Redirects non-grata user to homepage on visiting the greeting page. 
 */
class HelloWorldRedirectSubscriber implements EventSubscriberInterface {
    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;
    /**
     * @var \Drupal\Core\Routing\CurrentRouteMatch
     */
    protected $currentRouteMatch;

    /**
     * HelloWorldRedirectSubscriber constructor.
     * 
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
     */
    public function __construct(AccountProxyInterface $currentUser, CurrentRouteMatch $currentRouteMatch)
    {
        $this->currentUser = $currentUser;
        $this->currentRouteMatch = $currentRouteMatch;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents() {
        $events[KERNELEVENTS::REQUEST][] = ['onRequest', 0];
        return $events;
    }

    /**
     * Callback handler for Kernel's Request events.
     * 
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event) {
        $routeName = $this->currentRouteMatch->getRouteName();
        $roles = $this->currentUser->getRoles();

        // If user is not on the greeting page or if user is not non_grata, return.
        if ($routeName !== 'hello_world.hello' || !in_array('anonymous', $roles)) {
            return;
        }

        // Redirect to homepage.
        $url = Url::fromUri('internal:/');
        $event->setResponse(new LocalRedirectResponse($url->toString()));
    }
}