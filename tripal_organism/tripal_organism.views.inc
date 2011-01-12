<?php

/**
 *  @file
 *  This file contains the basic functions for views integration of
 *  chado/tripal organism tables. Supplementary functions can be found in
 *  ./views/
 *
 *  Documentation on views integration can be found at 
 *  http://views2.logrus.com/doc/html/index.html.
 */
 
/*************************************************************************
 * Implements hook_views_data()
 * Purpose: Describe chado/tripal tables & fields to views
 * @return: a data array which follows the structure outlined in the
 *   views2 documentation for this hook. Essentially, it's an array of table
 *   definitions keyed by chado/tripal table name. Each table definition 
 *   includes basic details about the table, fields in that table and
 *   relationships between that table and others (joins)
 */
require_once('views/organism.views.inc');
function tripal_organism_views_data()  {
  $data = array();
  
  $data = array_merge($data, retrieve_organism_views_data());
  
  return $data;
}

/*************************************************************************
 * Implements hook_views_handlers()
 * Purpose: Register all custom handlers with views
 *   where a handler describes either "the type of field", 
 *   "how a field should be filtered", "how a field should be sorted"
 * @return: An array of handler definitions
 */
function tripal_organism_views_handlers() {
 return array(
   'info' => array(
     'path' => drupal_get_path('module', 'tripal_organism') . '/views/handlers',
   ),
   'handlers' => array(
     'views_handler_field_computed_organism_nid' => array(
       'parent' => 'views_handler_field_numeric',
     ),
   ),
 );
}