<?php
  // Remove this pane if feature type is not a gene
  $feature  = $variables['node']->feature;
  if ($feature->type_id->name != 'gene') return;

  $gene_family_url = ""; // default
  $view = views_get_view('gene');
  foreach ($view->display as $part) {
    if (isset($part->display_options['fields']['gene_family']['alter'])) {
      $gene_family_url = $part->display_options['fields']['gene_family']['alter']['path'];
      $gene_family_url = preg_replace("/\[.*?\]/", '', $gene_family_url);

      // make sure there is a leading /
      if (!preg_match("/^\//", $gene_family_url) 
            && !preg_match("/^http/", $gene_family_url)) {
        $gene_family_url = "/$gene_family_url";
      }
    }
  }

  drupal_add_css($my_path . '/theme/css/basket.css');

  // So that chado variables are returned in an array
  $options = array('return_array' => 1);

  $feature  = $variables['node']->feature;  
  $feature_id = $feature->feature_id;
//echo "<pre>";var_dump($feature);echo "</pre>";

  // Get the overview record for this gene model
  $sql = "
    SELECT * FROM {gene}
    WHERE uniquename = '".$feature->uniquename."'";
  if ($res = chado_query($sql, array())) {
    $row = $res->fetchObject();
    $gene_family      = 'unknown';
    if ($row->gene_family != null)
        $gene_family      = $row->gene_family;
    $gene_description = gene_description_linkouts($row->description);
    $genus            = $row->genus;
    $species          = $row->species;
    $domains          = gene_domains_linkouts($row->domains);
  }
  else {
    $gene_family      = 'unknown';
    $gene_description = 'None given.';
    $genus            = 'unknown';
    $species          = 'unknown';
    $domains          = 'unknown';
  }

  // Get assembly version and gene model build (represented as analysis records)
  $gene_set = 'unknown';
  $feature = chado_expand_var($feature, 'table', 'analysisfeature', $options);
  foreach ($feature->analysisfeature as $af) {
    $af = chado_expand_var($af, 'table', 'analysisprop', $options);
    if (is_array($af->analysis_id->analysisprop)) {
      foreach ($af->analysis_id->analysisprop as $ap) {
        if ($ap->type_id->name == 'analysis_type' && $ap->value == 'gene model set') {
          $gene_set = $af->analysis_id->name;
        }
      }
    }
    else {
      $ap = $af->analysis_id->analysisprop;
      if ($ap->type_id->name == 'analysis_type' && $ap->value == 'gene model set') {
        $gene_set = $af->analysis_id->name;
      }
    }
  }
  $assembly = 'unknown';
  //echo "<pre>start with ";var_dump($feature); echo"</pre>";
  //NB: I (adf) would have expected that using the $options (return_array=>1)
  //here would have given me an array of featureloc, but it instead seemed
  //to make other things into arrays that I would NOT have expected to 
  //be arrays. Although I am not too comfortable with the code as it 
  //stands, it works and I don't envision genes having multiple featurelocs
  //so probably OK
  //$feature = chado_expand_var($feature, 'table', 'featureloc', $options);
  $feature = chado_expand_var($feature, 'table', 'featureloc');
  //echo "<pre>expanded to ";var_dump($feature); echo"</pre>";
  //adf: don't ask me why the structure is like so, but it is.
  $src = $feature->featureloc->feature_id->srcfeature_id;
  $src = chado_expand_var($src, 'table', 'analysisfeature', $options);
  foreach ($src->analysisfeature as $af) {
    $af = chado_expand_var($af, 'table', 'analysisprop', $options);
    if (is_array($af->analysis_id->analysisprop)) {
      foreach ($af->analysis_id->analysisprop as $ap) {
        if ($ap->type_id->name == 'analysis_type' && $ap->value == 'genome assembly') {
          $assembly = $af->analysis_id->name;
        }
      }
    }
    else {
      $ap = $af->analysis_id->analysisprop;
      if ($ap->type_id->name == 'analysis_type' && $ap->value == 'genome assembly') {
        $assembly = $af->analysis_id->name;
      }
    }
  }

  // Get properties
  $properties = array();
  $feature = chado_expand_var($feature, 'table', 'featureprop', $options);
  $props = $feature->featureprop;
  foreach ($props as $prop){
    $prop = chado_expand_var($prop, 'field', 'featureprop.value');
    $properties[$prop->type_id->name] = $prop->value;
  }

  // Expand relationships
  $mRNAs = array();
  $feature = chado_expand_var($feature, 'table', 'feature_relationship', $options);
  $related = $feature->feature_relationship->object_id;
  foreach ($related as $relative) {
    if ($relative->subject_id->type_id->name == 'mRNA') {
      $mRNAs[$relative->subject_id->name] = $relative->subject_id->uniquename;
    }
  }
  ksort($mRNAs);
  
  // Created linked domain names
  $doms = explode(' ', $domains);
  $links = array();
  foreach ($doms as $d) {
//eksc- not clear how these links should be formed. Some work this way, some don't.
//      do the protein domain features need to be synced?
//   $links[] = "<a href='/feature/consensus/consensus/polypeptide_domain/$d'>$d</a>";
//for now, just do this:
    $links[] = $d;
  }
  $domain_html = implode(', ', $links);
  
  
  ///////////////////////   SET UP JBROWSE   ////////////////////////

  $jbrowse_html = '';
  
  // Temporary hack: don't show if organism is glyma
  if ($feature->organism_id->abbreviation == 'glyma') {
    $gene_name = substr($feature->name, 6);
    $url = "https://www.soybase.org/gb2/gbrowse/gmax2.0/?q=$gene_name";
    $jbrowse_html = "
      If the Soybase.org GBrowse window does not open automatically, click 
      <a href='$url'>here</a> to see this gene model on the soybean genome.";
  }
  else {
    if ($feature->type_id->name == "gene") {
      // Try to get JBrowse URL from Chado
      if (!($jbrowse_info = getJBrowseURL($feature_id))) {
        $jbrowse_html = 'No browser instance available to display a graphic for this gene.';
      }
      else {
        $jbrowse_url = $jbrowse_info['url'] 
                 . '&loc='
                 . $jbrowse_info['chr'] . ':'
                 . $jbrowse_info['start'] . '..'
                 . $jbrowse_info['stop'];
    
        $jbrowse_html = "
          </br>   
          <div>
            <iframe id='frameviewer' frameborder='0' width='100%' height='1000' 
                    scrolling='yes' src='$jbrowse_url' name='frameviewer'></iframe>
          </div>";
      }//JBrowse instance exists
    }//feature is a gene
  }  
  
  ///////////////////////   PREPARE THE RECORD TABLE   ////////////////////////
  
  // the $headers array is an array of fields to use as the column headers. 
  // additional documentation can be found here 
  // https://api.drupal.org/api/drupal/includes%21theme.inc/function/theme_table/7
  // This table for the analysis has a vertical header (down the first column)
  // so we do not provide headers here, but specify them in the $rows array below.
  $headers = array();
  
  // the $rows array contains an array of rows where each row is an array
  // of values for each column of the table in that row.  Additional documentation
  // can be found here:
  // https://api.drupal.org/api/drupal/includes%21theme.inc/function/theme_table/7 
  $rows = array();
  
  // Name row
  $rows[] = array(
    array(
      'data' => 'Gene Model Name',
      'header' => TRUE,
      'width' => '20%',
    ),
    $feature->name
  );
  
  // Organism row
  $organism = $feature->organism_id->genus 
            . " " . $feature->organism_id->species 
            ." (" . $feature->organism_id->common_name .")";
  if (property_exists($feature->organism_id, 'nid')) {
    $text = "<i>" . $feature->organism_id->genus . " " 
           . $feature->organism_id->species 
           . "</i> (" . $feature->organism_id->common_name .")";
    $url = "node/".$feature->organism_id->nid;
    $organism = l($text, $url, array('html' => TRUE));
  } 
  $rows[] = array(
    array(
      'data' => 'Organism',
      'header' => TRUE,
    ),
    $organism
  );

  // Assembly version
  $rows[] = array(
    array(
      'data' => 'Assembly version',
      'header' => TRUE,
    ),
    $assembly
  );
  
  // Gene models set
  $rows[] = array(
    array(
      'data' => 'Gene Model Build',
      'header' => TRUE,
      'width' => '20%',
    ),
    $gene_set
  );
  
  // Gene family rows
 
  if ($gene_family == 'unknown') {
     $gene_family_html = "<b> not assigned to a gene family</b>";
  }
  else {
    $url = $gene_family_url . $gene_family;
    $gene_family_html = "<a href='$url'>$gene_family</a>";
  }

  $rows[] = array(
    array(
      'data' => 'Gene Family',
      'header' => TRUE,
      'width' => '20%',
    ),
    $gene_family_html
  );
   //tagged terms only if they exists.
  if($ans !=null)
  {
   $rows[] = array(
    array(
      'data' => 'Tagged terms',
      'header' => TRUE,
      'width' => '20%',
    ),
    $ans
  );
}
  // Description row
  $rows[] = array(
    array(
      'data' => 'Description',
      'header' => TRUE,
      'width' => '20%',
    ),
    array(
      'data' => $gene_description,
    )
  );

  // Protein domains
  $rows[] = array(
    array(
      'data'   => 'Protein domains',
      'header' => true,
      'width'  => '20%', 
    ),
    array(
      'data' => $domains,
    )
  );
  
  // mRNA(s)
  $mRNA_html = '';
  foreach (array_keys($mRNAs) as $mRNA_name) {
    $url = "?pane=Sequences#$mRNA_name";
    $mRNA_html .= "<a href='$url'>$mRNA_name</a><br>";
  }
  $rows[] = array(
    array(
      'data' => 'mRNA and protein identifiers<br>(also see Sequences tab)',
      'header' => TRUE,
      'width' => '20%',
    ),
    $mRNA_html
  );
  
  // the $table array contains the headers and rows array as well as other
  // options for controlling the display of the table.  Additional
  // documentation can be found here:
  // https://api.drupal.org/api/drupal/includes%21theme.inc/function/theme_table/7
  $table = array(
    'header' => $headers,
    'rows' => $rows,
    'attributes' => array(
      'id' => 'tripal_feature-table-base',
      'class' => 'tripal-data-table'
    ),
    'sticky' => FALSE,
    'caption' => '',
    'colgroups' => array(),
    'empty' => '',
  );
  
  // once we have our table array structure defined, we call Drupal's theme_table()
  // function to generate the table.
  print theme_table($table); 
  
  if ($jbrowse_html) {
    print $jbrowse_html;
  }

