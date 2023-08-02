<?php

namespace Drupal\new_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a new block block.
 *
 * @Block(
 *   id = "new_block_new_block",
 *   admin_label = @Translation("new block"),
 *   category = @Translation("Custom")
 * )
 */
class NewBlockBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'title_block' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['title_block'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Title'),
      '#target_type' => 'node',
      '#selection_settings' => [
        'target_bundles' => ['article'],
      ],
    ];
    if (!empty($this->configuration['title_block'])) {
      $form['title_block']['#default_value'] = Node::load($this->configuration['title_block']);
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['title_block'] = $form_state->getValue('title_block');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $node_id = $this->configuration['title_block'];
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($node_id);
    $build = [];
    if ($node) {
      $build = [
        '#markup' => $node->label(),
      ];
    }
    return $build;
  }

}
