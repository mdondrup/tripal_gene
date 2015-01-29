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
