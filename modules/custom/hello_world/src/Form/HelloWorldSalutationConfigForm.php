<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration Form to save custom salutation message.
 */
class HelloWorldSalutationConfigForm extends ConfigFormBase {

    protected $logger;

    public function __construct(ConfigFactoryInterface $config_factory, LoggerChannelInterface $logger)
    {
        parent::__construct($config_factory);
        $this->logger = $logger;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
          $container->get('config.factory'),
          $container->get('hello_world.logger.channel.hello_world')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['hello_world.custom_salutation'];
    }
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'hello_world_salutation_config_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('hello_world.custom_salutation');

        $form['salutation'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Custom Salutation Message'),
            '#description' => $this->t('Enter a custom greeting message <20 characters'),
            '#default_value' => $config->get('salutation'),
        );

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if (strlen($form_state->getValue('salutation')) > 20) {
            $form_state->setErrorByName('salutation', $this->t('Please enter a shorter greeting message.'));
        }

        return parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $config = $this->config('hello_world.custom_salutation');
        $config->set('salutation', $form_state->getValue('salutation'));
        $config->save();

        // Log the changed greeting message.
        $this->logger->info('The Hello World greeting message has been set to @msg', array('@msg' => $form_state->getValue('salutation')));
        return parent::submitForm($form, $form_state);
    }
}