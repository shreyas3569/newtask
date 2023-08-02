<?php

namespace Drupal\new_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Use of our custom service.
 */
class CustomController extends ControllerBase {

  /**
   * This is the function hello.
   */
  public function hello() {
    $node_id = 1;
    $node = Node::load($node_id);
    if ($node && $node->getType() == 'shapes') {
      $node_title = $node->getTitle();
      $taxonomy_term_name = '';
      $taxonomy_term_references = $node->get('field_colors')->referencedEntities();
      if (!empty($taxonomy_term_references)) {
        $taxonomy_term = reset($taxonomy_term_references);
        $taxonomy_term_name = $taxonomy_term->getName();
      }
      $user_name = '';
      $user_references = $taxonomy_term->get('field_userss')->referencedEntities();
      if (!empty($user_references)) {
        $user = reset($user_references);
        $user_name = $user->getDisplayName();
      }
      return [
        '#markup' => $node_title . ' ' . $taxonomy_term_name . ' ' . $user_name,
      ];
    }
  }

}
