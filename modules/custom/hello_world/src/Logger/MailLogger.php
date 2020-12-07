<?php

namespace Drupal\hello_world\Logger;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class that sends emails whenever an error is encountered.
 */
class MailLogger implements LoggerInterface {
    protected $parser;
    protected $configFactory;

    public function __construct(LogMessageParserInterface $parser, ConfigFactoryInterface $config_factory)
    {
        $this->parser = $parser;
        $this->configFactory = $config_factory;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array()) {
        // Send an email.
        $to = $this->configFactory->get('system.site')->get('mail');
        $langcode = $this->configFactory->get('system.site')->get('langcode');
        $variables = $this->parser->parseMessagePlaceholders($message, $context);
        $markup = new FormattableMarkup($message, $variables);
        \Drupal::service('plugin.manager.mail')->mail('hello_world', 'hello_world_log', $to, $langcode, ['message' => $markup]);
    }

    public function emergency($message, array $context = array())
    {
        // Add code when emergency is to be logged.
    }

    public function alert($message, array $context = array())
    {
        // Add code when alert messages are to be logged.
    }

    public function critical($message, array $context = array())
    {
        // Add code when critical messages are to be logged.
    }

    public function error($message, array $context = array())
    {
        // Add code when errors are to be logged.
    }

    public function warning($message, array $context = array())
    {
        // Add code when warnings are to be logged.
    }

    public function notice($message, array $context = array())
    {
        // Add code when notices are to be logged.
    }

    public function info($message, array $context = array())
    {
        // Add code when info is to be logged.
    }

    public function debug($message, array $context = array())
    {
        // Add code when debug messages are to be logged
    }
}