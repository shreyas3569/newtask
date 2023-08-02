<?php

namespace Drupal\new_exercise\Controller;

// Namespace of this file.
// Use of controllerbase.
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Use of our custom service.
 */
class MyController extends ControllerBase {

  /**
   * Function helloo.
   */
  public function helloo() {
    $node_id = 1;
    $node = Node::load($node_id);
    if ($node->getType() === 'shapes') {
      $node_title = $node->getTitle();
      $taxonomy_term = $node->get('field_colors')->entity;
      $taxonomy_term_name = $taxonomy_term->getName();
      $user_references = $taxonomy_term->get('field_userss')->entity;
      $user_name = $user_references->getDisplayName();
      $build = [
        '#markup' => $node_title . ' | ' . $taxonomy_term_name . ' | ' . $user_name,
      ];
      return $build;
    }
  }

}
