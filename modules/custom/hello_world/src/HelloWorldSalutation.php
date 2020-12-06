<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\hello_world\HelloWorldSalutationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the HelloSalutation service.
 */
class HelloWorldSalutation {
    use StringTranslationTrait;

    protected $config_factory;
    protected $eventDispatcher;

    /**
     * Constructor for this service.
     */
    public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->configFactory = $config_factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Returns greeting depending on time of day if custom greeting not set.
     */
    public function getSalutation() {
        $config = $this->configFactory->get('hello_world.custom_salutation');

        // Add breadcrumb to home.
        $url = Url::fromRoute('<front>');
        $link = Link::fromTextAndUrl('Home', $url);
        $breadcrumb = $this->t('Go back to @link', ['@link' => $link->toString()]);

        // If custom salutation message exists, return that.
        $salutation = $config->get('salutation');
        if ($salutation != "") {
            // First, set the salutation value as per the config form.
            $hello_world_salutation_event = new SalutationEvent();
            $hello_world_salutation_event->setValue($salutation);
            // Then, whenever this method is called, dispatch HelloWorldSalutationEvent
            // so that others can override the salutation message.
            $hello_world_event = $this->salutation_event->dispatch(SalutationEvent::EVENT, $hello_world_salutation_event);
            // Finally, use the overridden value.
            return $breadcrumb . '<br />' . $hello_world_salutation_event->getValue();
        }

        // Fallback to greeting based on time of day.
        $time = new \DateTime(); 
        if ((int) $time->format('G') > 0 && (int) $time->format('G') <= 12) {
          return $this->t('Good morning world');
        }
        elseif ((int) $time->format('G') > 12 && (int) $time->format('G') <= 18) {
            return $this->t('Good afternoon world');
        }
        elseif ((int) $time->format('G') > 18) {
            return $this->t('Good evening world');
        }
    }
}