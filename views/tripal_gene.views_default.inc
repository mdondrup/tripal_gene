<?php
/**
 * @file
 * Gene default views
 */

/**
 * Implements hook_views_default_views().
 *
 * @ingroup tripal_organism
 */
function tripal_gene_views_default_views() {
  $views = array();

  // User View ("Search Biological Content")
  // Remember, if you change the name/path of this view,
  // you also want to change it's description in tripal_organism_search_biological_data_views()
  $view = tripal_gene_defaultvalue_user_gene();
  $view = tripal_make_view_compatible_with_external($view);
  $views[$view->name] = $view;

  return $views;
}

/**
 * Defines the gene user search default view.
 *
 * @ingroup tripal_gene
 */

function tripal_gene_defaultvalue_user_gene(){
   
$view = new view();
$view->name = 'gene_family';
$view->description = '';
$view->tag = 'default';
$view->base_table = 'gene';
$view->human_name = 'Gene Family';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Gene Family';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '10';
$handler->display->display_options['style_plugin'] = 'table';
$handler->display->display_options['style_options']['columns'] = array(
  'nid' => 'nid',
  'name' => 'name',
  'common_name' => 'common_name',
  'coordinate' => 'coordinate',
  'description' => 'description',
  'gene_family' => 'gene_family',
  'gene_id' => 'gene_id',
  'start' => 'start',
  'stop' => 'stop',
  'strand' => 'strand',
  'actions' => 'actions',
);
$handler->display->display_options['style_options']['default'] = '-1';
$handler->display->display_options['style_options']['info'] = array(
  'nid' => array(
    'sortable' => 0,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'name' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'common_name' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'coordinate' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'description' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'gene_family' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'gene_id' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'start' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'stop' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'strand' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'actions' => array(
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
);
/* Header: Global: Text area */
$handler->display->display_options['header']['area']['id'] = 'area';
$handler->display->display_options['header']['area']['table'] = 'views';
$handler->display->display_options['header']['area']['field'] = 'area';
$handler->display->display_options['header']['area']['content'] = ' <a href="basket">Go to Bucket</a> ';
$handler->display->display_options['header']['area']['format'] = '1';
/* Field: Array: Nid */
$handler->display->display_options['fields']['nid']['id'] = 'nid';
$handler->display->display_options['fields']['nid']['table'] = 'gene';
$handler->display->display_options['fields']['nid']['field'] = 'nid';
$handler->display->display_options['fields']['nid']['exclude'] = TRUE;
$handler->display->display_options['fields']['nid']['separator'] = '';
/* Field: Array: Name */
$handler->display->display_options['fields']['name']['id'] = 'name';
$handler->display->display_options['fields']['name']['table'] = 'gene';
$handler->display->display_options['fields']['name']['field'] = 'name';
$handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['name']['alter']['path'] = 'node/[nid]';
/* Field: Array: Common Name */
$handler->display->display_options['fields']['common_name']['id'] = 'common_name';
$handler->display->display_options['fields']['common_name']['table'] = 'gene';
$handler->display->display_options['fields']['common_name']['field'] = 'common_name';
$handler->display->display_options['fields']['common_name']['label'] = 'Species';
$handler->display->display_options['fields']['common_name']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['common_name']['alter']['path'] = '[common_name]';
/* Field: Array: Coordinate */
$handler->display->display_options['fields']['coordinate']['id'] = 'coordinate';
$handler->display->display_options['fields']['coordinate']['table'] = 'gene';
$handler->display->display_options['fields']['coordinate']['field'] = 'coordinate';
$handler->display->display_options['fields']['coordinate']['label'] = 'Chromosome';
/* Field: Array: Description */
$handler->display->display_options['fields']['description']['id'] = 'description';
$handler->display->display_options['fields']['description']['table'] = 'gene';
$handler->display->display_options['fields']['description']['field'] = 'description';
/* Field: Array: Gene Family */
$handler->display->display_options['fields']['gene_family']['id'] = 'gene_family';
$handler->display->display_options['fields']['gene_family']['table'] = 'gene';
$handler->display->display_options['fields']['gene_family']['field'] = 'gene_family';
/* Field: Array: Gene Id */
$handler->display->display_options['fields']['gene_id']['id'] = 'gene_id';
$handler->display->display_options['fields']['gene_id']['table'] = 'gene';
$handler->display->display_options['fields']['gene_id']['field'] = 'gene_id';
$handler->display->display_options['fields']['gene_id']['exclude'] = TRUE;
/* Field: Array: Start */
$handler->display->display_options['fields']['start']['id'] = 'start';
$handler->display->display_options['fields']['start']['table'] = 'gene';
$handler->display->display_options['fields']['start']['field'] = 'start';
$handler->display->display_options['fields']['start']['separator'] = '';
/* Field: Array: Stop */
$handler->display->display_options['fields']['stop']['id'] = 'stop';
$handler->display->display_options['fields']['stop']['table'] = 'gene';
$handler->display->display_options['fields']['stop']['field'] = 'stop';
$handler->display->display_options['fields']['stop']['separator'] = '';
/* Field: Array: Strand */
$handler->display->display_options['fields']['strand']['id'] = 'strand';
$handler->display->display_options['fields']['strand']['table'] = 'gene';
$handler->display->display_options['fields']['strand']['field'] = 'strand';
$handler->display->display_options['fields']['strand']['separator'] = '';
/* Field: Gene: Bucket */
$handler->display->display_options['fields']['actions']['id'] = 'actions';
$handler->display->display_options['fields']['actions']['table'] = 'gene';
$handler->display->display_options['fields']['actions']['field'] = 'actions';
/* Filter criterion: Array: Name */
$handler->display->display_options['filters']['name']['id'] = 'name';
$handler->display->display_options['filters']['name']['table'] = 'gene';
$handler->display->display_options['filters']['name']['field'] = 'name';
$handler->display->display_options['filters']['name']['exposed'] = TRUE;
$handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
$handler->display->display_options['filters']['name']['expose']['label'] = 'Name';
$handler->display->display_options['filters']['name']['expose']['use_operator'] = TRUE;
$handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
$handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
$handler->display->display_options['filters']['name']['expose']['remember_roles'] = array(
  2 => '2',
  1 => 0,
  4 => 0,
);
$handler->display->display_options['filters']['name']['max_length'] = '40';
/* Filter criterion: Array: Description */
$handler->display->display_options['filters']['description']['id'] = 'description';
$handler->display->display_options['filters']['description']['table'] = 'gene';
$handler->display->display_options['filters']['description']['field'] = 'description';
$handler->display->display_options['filters']['description']['exposed'] = TRUE;
$handler->display->display_options['filters']['description']['expose']['operator_id'] = 'description_op';
$handler->display->display_options['filters']['description']['expose']['label'] = 'Description';
$handler->display->display_options['filters']['description']['expose']['use_operator'] = TRUE;
$handler->display->display_options['filters']['description']['expose']['operator'] = 'description_op';
$handler->display->display_options['filters']['description']['expose']['identifier'] = 'description';
$handler->display->display_options['filters']['description']['expose']['remember_roles'] = array(
  2 => '2',
  1 => 0,
  4 => 0,
);
/* Filter criterion: Array: Gene Family */
$handler->display->display_options['filters']['gene_family']['id'] = 'gene_family';
$handler->display->display_options['filters']['gene_family']['table'] = 'gene';
$handler->display->display_options['filters']['gene_family']['field'] = 'gene_family';
$handler->display->display_options['filters']['gene_family']['exposed'] = TRUE;
$handler->display->display_options['filters']['gene_family']['expose']['operator_id'] = 'gene_family_op';
$handler->display->display_options['filters']['gene_family']['expose']['label'] = 'Gene Family';
$handler->display->display_options['filters']['gene_family']['expose']['use_operator'] = TRUE;
$handler->display->display_options['filters']['gene_family']['expose']['operator'] = 'gene_family_op';
$handler->display->display_options['filters']['gene_family']['expose']['identifier'] = 'gene_family';
$handler->display->display_options['filters']['gene_family']['expose']['remember_roles'] = array(
  2 => '2',
  1 => 0,
  4 => 0,
);

/* Display: Gene_Bucket_Page */
$handler = $view->new_display('page', 'Gene_Bucket_Page', 'page');
$handler->display->display_options['path'] = 'gene';

return $view;

}
