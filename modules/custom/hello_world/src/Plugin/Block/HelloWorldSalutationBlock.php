<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Hello World Salutation Block.
 * 
 * @Block(
 *   id = "hello_world_salutation_block",
 *   admin_label = @Translation("Hello World Salutation Block"),
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface {
    protected $salutation;

    public function __construct(array $configuration, $plugin_id, $plugin_definition, HelloWorldSalutationService $salutation)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->salutation = $salutation;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('hello_world.salutation')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
        $config = $this->getConfiguration();
        $markup = $config['enabled'] ? $this->salutation->getSalutation() : $this->t('This block is disabled.');
        return [
            '#markup' => $markup
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [
            'enabled' => 1
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $config = $this->getConfiguration();

        $form['enabled'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Enabled'),
            '#description' => $this->t('Uncheck this box to disable the block.'),
            '#default_value' => $config['enabled'],
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
        $this->configuration['enabled'] = $form_state->getValue('enabled');
    }
}