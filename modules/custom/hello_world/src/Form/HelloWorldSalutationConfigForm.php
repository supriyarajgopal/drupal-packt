<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration Form to save custom salutation message.
 */
class HelloWorldSalutationConfigForm extends ConfigFormBase {

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

        return parent::submitForm($form, $form_state);
    }
}