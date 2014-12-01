<?php

function tripal_gene_views_data(){
    $data=array();
    $data['gene']['table']['group']=t('Gene');
    $data['gene']['table']['base'] = array(
    'field' => 'nid', // This is the identifier field for the view.
    'title' => t('gene table'),
    'help' => t('gene table contains example content.'),
    'weight' => -10,
  );

    $data['gene']['table']['nid'] = array (
        'title' => t('nid'),
        'help' => t('Node ID'),
        'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
        ),
        'filter' => array(
        'handler' => 'views_handler_filter_numeric',
        ),
        'sort' => array(
        'handler' => 'views_handler_sort',
        ),
     ); 

    $data['gene']['table']['gene_id'] = array (
        'title' => t('gene_id'),
        'help' => t('Gene ID'),
        'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
        ),
        'filter' => array(
        'handler' => 'views_handler_filter_numeric',
        ),
        'sort' => array(
        'handler' => 'views_handler_sort',
        ),
     ); 

    
   $data['gene']['table']['organism_id'] = array (
        'title' => t('organism_id'),
        'help' => t('Organism ID'),
        'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
        ),
        'filter' => array(
        'handler' => 'views_handler_filter_numeric',
        ),
        'sort' => array(
        'handler' => 'views_handler_sort',
        ),
     );      

      $data['gene']['table']['srcfeature_id'] = array (
        'title' => t('srcfeature_id'),
        'help' => t('src ID'),
        'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
        ),
        'filter' => array(
        'handler' => 'views_handler_filter_numeric',
        ),
        'sort' => array(
        'handler' => 'views_handler_sort',
        ),
     );

     $data['gene']['table']['common_name'] = array (
        'title' => t('Common Name'),
        'help' => t('Organism name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE, 
        ), 
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ), 
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ), 
      ); 


    $data['gene']['table']['name'] = array (
        'title' => t('Gene name'),
        'help' => t('Gene name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );

  
    $data['gene']['table']['uniquename'] = array (
        'title' => t('Unique Name'),
        'help' => t('Organism Unique  name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
       );

     $data['gene']['table']['coordinate'] = array (
        'title' => t('Chromosome'),
        'help' => t('Chromosome name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );

  
      $data['gene']['table']['description'] = array (
        'title' => t('Description'),
        'help' => t('description'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );

     $data['gene']['table']['gene_family'] = array (
        'title' => t('Gene Family'),
        'help' => t('Gene family name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );

   $data['gene']['table']['stop'] = array (
        'title' => t('END'),
        'help' => t('stop location'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );


    $data['gene']['table']['start'] = array (
        'title' => t('Start'),
        'help' => t('Begin location'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );

    $data['gene']['table']['strand'] = array (
        'title' => t('Strand'),
        'help' => t('Strand name'),
        'field' => array( 
        'handler' => 'views_handler_field', 
        'click sortable' => TRUE,  
        ),
        'sort' => array( 
        'handler' => 'views_handler_sort', 
        ), 
       'filter' => array( 
        'handler' => 'views_handler_filter_string',
       ),   
       'argument' => array( 
         'handler' => 'views_handler_argument_string',
         ),
      );
 return $data;
}



function tripal_gene_views_data_alter(&$data) {
  $data['gene']['actions'] = array(
   'title' => t('Bucket'),
   'help' => t('Clickable links to actions a user may perform on a Node.'),
   'field' => array(
   'handler' => 'tripal_bucket_views_handler_field_actions',
   'group' => 'Gene',
   'click sortable' => FALSE,
   ),
 );
}


