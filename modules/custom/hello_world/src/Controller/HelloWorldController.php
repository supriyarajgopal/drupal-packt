<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the salutation message.
 */
class HelloWorldController extends ControllerBase {

    /**
     * Salutation service object.
     */
    protected $salutation;

    /**
     * Constructor to pass the service object.
     */
    public function __construct(HelloWorldSalutation $salutation) {
        $this->salutation = $salutation;
    }

    /**
     * Inject services.
     */
    public static function create(ContainerInterface $container) {
        return new static (
            $container->get('hello_world.salutation')
        );
    }

    /**
     * Hello World.
     * 
     * @return array
     */
    public function helloWorld() {
        return $this->salutation->getSalutationComponent();
    }
}