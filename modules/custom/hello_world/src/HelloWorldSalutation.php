<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\hello_world\EventDispatcher\HelloWorldSalutationEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the HelloSalutation service.
 */
class HelloWorldSalutation {
    use StringTranslationTrait;

    protected $configFactory;
    protected $eventDispatcher;

    /**
     * Constructor for this service.
     */
    public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $event_dispatcher)
    {
        $this->configFactory = $config_factory;
        $this->eventDispatcher = $event_dispatcher;
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
            $salutation_event = new HelloWorldSalutationEvent();
            $salutation_event->setValue($salutation);
            // Then, whenever this method is called, dispatch HelloWorldSalutationEvent
            // so that others can override the salutation message.
            $event = $this->eventDispatcher->dispatch(HelloWorldSalutationEvent::EVENT, $salutation_event);
            // Finally, use the overridden value.
            return $breadcrumb . '<br />' . $salutation_event->getValue();
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

    /**
     * Returns the Salutation render array.
     */
    public function getSalutationComponent() {
        $render_array = [
          '#theme' => 'hello_world_salutation',
        ];

        $config = $this->configFactory->get('hello_world.custom_salutation');

        // If custom salutation message exists, return that.
        $salutation = $config->get('salutation');
        if ($salutation != "") {
            // First, set the salutation value as per the config form.
            $salutation_event = new HelloWorldSalutationEvent();
            $salutation_event->setValue($salutation);
            // Then, whenever this method is called, dispatch HelloWorldSalutationEvent
            // so that others can override the salutation message.
            $event = $this->eventDispatcher->dispatch(HelloWorldSalutationEvent::EVENT, $salutation_event);
            $render_array['#overridden'] = TRUE;
            // Finally, use the overridden value.
            return $render_array;
        }

        // Fallback to greeting based on time of day.
        $render_array['#target'] = $this->t('world');
        $time = new \DateTime();
        if ((int) $time->format('G') > 0 && (int) $time->format('G') <= 12) {
          $render_array['#salutation'] = $this->t('Good morning');
        }
        elseif ((int) $time->format('G') > 12 && (int) $time->format('G') <= 18) {
          $render_array['#salutation'] = $this->t('Good afternoon');
        }
        elseif ((int) $time->format('G') > 18) {
          $render_array['#salutation'] = $this->t('Good evening');
        }
        return $render_array;
    }
}