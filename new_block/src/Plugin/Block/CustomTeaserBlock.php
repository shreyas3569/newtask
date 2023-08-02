<?php

namespace Drupal\new_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a custom block that loads a node in teaser mode.
 *
 * @Block(
 *   id = "custom_teaser_node_block",
 *   admin_label = @Translation("Custom Teaser Node Block"),
 *   category = @Translation("Custom Blocks")
 * )
 */
class CustomTeaserBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;

  /**
   * Constructs a new CustomTeaserNodeBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $entityTypeManager, EntityDisplayRepositoryInterface $entityDisplayRepository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->entityDisplayRepository = $entityDisplayRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository')
    );
  }



  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['title_block'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Title'),
      '#target_type' => 'node',
      '#selection_settings' => [
        'target_bundles' => ['article'],
      ],
    ];

    $view_modes = $this->entityDisplayRepository->getViewModes('node');
    $options = [];
    foreach ($view_modes as $view_mode => $info) {
      $options[$view_mode] = $info['label'];
    }

    $form['view_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('View Mode'),
      '#options' => $options,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['title_block'] = $form_state->getValue('title_block');
    $this->configuration['view_mode'] = $form_state->getValue('view_mode');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Load the node in teaser mode.
    $node_id = $this->configuration['title_block'];
    $node = $this->entityTypeManager->getStorage('node')->load($node_id);

    // Render the node entity in teaser mode.
  if ($node) {
      $view_mode = $this->configuration['view_mode'];
      $view_builder = $this->entityTypeManager->getViewBuilder('node');
      $build = $view_builder->view($node, $view_mode);
    }

    return $build;
  }
}
