<?php

namespace Drupal\hello_world\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;

/**
 * Defines an event dispatcher that allows other modules to modify salutation configuration message.
 */
class HelloWorldSalutationEvent extends Event {
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