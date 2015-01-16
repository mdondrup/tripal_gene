<?php

/**
 * @file
 * handler class for the new field 'basket'
 */

/**
 * class definition
 */
class tripal_gene_views_handler_field_actions extends views_handler_field {
  /**
   * Render function: return html output
  */
  function render($values) {

      foreach ($values as $key => $val) {
          if ($key == "gene_name") {

              $sql =<<<SQL
              SELECT *   
              FROM  chado.gene
              WHERE name = :name
SQL;

              $args = array(':name' => $val);               
              $result = chado_query($sql, $args)->fetchObject();
              $vars = get_object_vars ( $result );

              // filling $extra only with the elements corresponding to the list of keys below:
              $keys = array('name', 'stop', 'start', 'coordinate', 'gene_family', 'description');
              $extra = array_intersect_key($vars, array_fill_keys($keys, 1));
          }      
      }

      return lightshop_add2basket_link($result->nid);
   }

    function query() {
        //do nothing but keep the function here;
    }

}

