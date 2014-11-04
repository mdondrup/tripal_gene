<?php
/**
 * @file
 * Implement the gene node content type
 */

/**
 * Implementation of hook_node_info().
 *
 * This node_info, is a simple node that describes the functionallity of the module. It specifies
 * that the title(Biomaterial Name) and body(Description) set to true so that they information can be
 * entered
 *
 * @ingroup tripal_gene
 */
function tripal_gene_node_info() {
  return array(
    'chado_gene' => array(
      'name'        => t('Gene'),
      'base'        => 'chado_gene',
      'description' => t('A gene from the Chado database'),
      'has_title'   => TRUE,
      'locked'      => TRUE,
      'chado_node_api' => array(
        'base_table' => 'gene',
        'hook_prefix' => 'chado_gene',
        'record_type_title' => array(
          'singular' => t('Gene'),
          'plural' => t('Genes')
        ),
        'sync_filters' => array(
          'type_id' => FALSE,
          'organism_id' => FALSE
        ),
      ),
    ),
  );
}

function chado_gene_insert($node){
   $check_org_id = chado_get_id_from_nid('gene', $node->nid);
   debug($check_org_id);
   if(!$check_org_id) {
    $record = new stdClass();
    $record->nid = $node->nid;
    $record->vid = $node->vid;
    $record->gene_id = $gene_id;
    drupal_write_record('chado_gene', $record);
  }

}


/**
 * Implements hook_node_view().
 *
 * @ingroup tripal_gene
 */
function tripal_gene_node_view($node, $view_mode, $langcode) {
  switch ($node->type) {
    case 'chado_gene':
      // Show feature browser and counts
      if ($view_mode == 'full') {
        $node->content['tripal_gene_base'] = array(
          '#markup' => theme('tripal_gene_base', array('node' => $node)),
          '#tripal_toc_id'    => 'base',
          '#tripal_toc_title' => 'Overview',
          '#weight' => -100,
        );
        $node->content['tripal_gene_organism'] = array(
          '#markup' => theme('tripal_gene_organism', array('node' => $node)),
          '#tripal_toc_id'    => 'organisms',
          '#tripal_toc_title' => 'Organisms',
        );
        $node->content['tripal_gene_properties'] = array(
          '#markup' => theme('tripal_gene_properties', array('node' => $node)),
          '#tripal_toc_id'    => 'properties',
          '#tripal_toc_title' => 'Properties',
        );
        $node->content['tripal_gene_project'] = array(
          '#markup' => theme('tripal_gene_project', array('node' => $node)),
          '#tripal_toc_id'    => 'projects',
          '#tripal_toc_title' => 'Projects',
        );
        $node->content['tripal_gene_stock'] = array(
          '#markup' => theme('tripal_gene_stock', array('node' => $node)),
          '#tripal_toc_id'    => 'stocks',
          '#tripal_toc_title' => 'Stocks',
        );
      }
      if ($view_mode == 'teaser') {
        $node->content['tripal_gene_teaser'] = array(
          '#markup' => theme('tripal_gene_teaser', array('node' => $node)),
        );
      }
      break;
  }
}

/**
 * Implements hook_node_insert().
 * Acts on all content types.
 *
 * @ingroup tripal_gene
 */
function chado_gene_chado_node_default_url_format() {
  return '/gene/[gene.gene_id]';
}
