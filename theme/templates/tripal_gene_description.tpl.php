<?php

$gene_id = $variables['node']->feature->feature_id;

$result = db_query('SELECT description FROM chado.gene WHERE gene_id = :uid', array(':uid' => $gene_id));


foreach($result as $value){
  print $value->description;
}




