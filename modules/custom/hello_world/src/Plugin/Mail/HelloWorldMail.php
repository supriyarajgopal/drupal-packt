<?php

use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Mail\MailFormatHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;
/**
 * @Mail(
 *   id = "hello_world_mail",
 *   label = @Translation("Hello World Mail"),
 * )
 */
class HelloWorldMail implements MailInterface, ContainerFactoryPluginInterface {
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static();
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $message) {
        $message['body'] = implode("\n\n", $message['body']);
        $message['body'] = MailFormatHelper::wrapMail($message['body']);
    }

    /**
     * {@inheritdoc}
     */
    public function mail(array $message) {
        // Use external API to send out the email.
    }
}