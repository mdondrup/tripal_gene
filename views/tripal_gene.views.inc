<?php

function tripal_gene_views_data_alter(&$data) {
    $data['gene']['actions'] = array(
        'title' => t('Bucket'),
        'help' => t('Clickable links to actions a user may perform on a Node.'),
        'field' => array(
            'handler' => 'tripal_gene_views_handler_field_actions',
            'group' => 'Gene',
            'click sortable' => FALSE,
        ),
    );
}

function tripal_gene_views_post_execute(&$view) {
    if($GLOBALS['all']) {
        module_load_include('inc', 'lightshop', 'lightshop.pages');
        $results = $view->result;

        foreach ($results as $res) {
            $sql =<<<SQL
            select g.*,
                   chf.nid
            from   chado.gene g,
                   public.chado_feature chf
            where  g.gene_id = chf.feature_id
                   and g.name = :name
SQL;
            $args = array(':name' => $res->gene_name);
            $gene = chado_query($sql, $args)->fetchObject();

            if ($gene) {
                $node = node_load($gene->nid);
                lightshop_add2basket($node);
            } 
        }
    }
}