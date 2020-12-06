<?php

namespace Drupal\hello_world;

use Symfony\Component\EventDispatcher\Event;

/**
 * Defines an event dispatcher that allows other modules to modify salutation configuration message.
 */
class SalutationEvent extends Event {
    const EVENT = 'hello_world.salutation_event';

    /**
     * The modifiable salutation message.
     * 
     * @var string
     */
    protected $message;

    /**
     * Getter method.
     * 
     * @return mixed $message
     */
    public function getValue() {
        return $this->message;
    }

    /**
     * Setter method.
     * 
     * @param mixed $message
     */
    public function setValue($message) {
        $this->message = $message;
    }
}