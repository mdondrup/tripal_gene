<?php

/**
 * Implements hook_node_view().
 *
 * @ingroup tripal_gene
 */
function tripal_gene_node_view($node, $view_mode, $langcode) {
  switch ($node->type) {
    case 'chado_feature':
      // Show feature browser and counts
      if ($view_mode == 'full') {          
        $node->content['tripal_gene_base'] = array(
          '#markup' => theme('tripal_gene_base', array('node' => $node)),
          '#tripal_toc_id'    => 'Description',
          '#tripal_toc_title' => 'Description',
          '#weight' => -100,
        );
        $node->content['tripal_gene_sequence'] = array(
          '#markup' => theme('tripal_gene_sequence', array('node' => $node)),
          '#tripal_toc_id'    => 'Sequences',
          '#tripal_toc_title' => 'Sequences',
          '#weight' => -90,
        );
        $node->content['tripal_gene_expression'] = array(
          '#markup' => theme('tripal_gene_expression', array('node' => $node)),
          '#tripal_toc_id'    => 'Expression',
          '#tripal_toc_title' => 'Expression',
          '#weight' => -80,
        );
      }
      break;
  }
}
