<?php
/**
 * file2arr()
 *
 * Local function to read association files describing JBrowse links into an array
 */
function file2arr($file) {
  if (!is_file($file) || !is_readable($file)) {
    print "Alias file '$file' not found in ".getcwd() .". Please create alias file.</br>";
    return false;
  }
  $arr = [];
  $fp = fopen($file,"r");
  while (false != ($line = fgets($fp,4096))) {
      if (!preg_match("/.+\s.+/",$line,$match)) continue;
      $tmp = preg_split("/\s/",trim($line));
      $arr[$tmp[0]] = $tmp[1];
  }
  fclose ($fp);

  return $arr;
}//file2arr()

  // Remove this pane if feature type is not a gene
  $feature  = $variables['node']->feature;
  if ($feature->type_id->name != 'gene') return


//TODO: this should be a db record
  // Get gene family URL prefix from the view.
  //   Note that the URL takes the gene model name rather than gene family name.
  $gene_family_url = "/chado_gene_phylotree_v2/"; // default
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
    $gene_description = $row->description;
    $genus            = $row->genus;
    $species          = $row->species;
    $domains          = $row->domains;
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
  $assembly = 'unknown';
  $feature = chado_expand_var($feature, 'table', 'analysisfeature', $options);
//echo "<pre>";var_dump($feature->analysisfeature);echo "</pre>";
  foreach ($feature->analysisfeature as $af) {
    $af = chado_expand_var($af, 'table', 'analysisprop', $options);
//echo "ONE ANALYSIS:<pre>";var_dump($af->analysis_id);echo "</pre>";
    if (is_array($af->analysis_id->analysisprop)) {
      foreach ($af->analysis_id->analysisprop as $ap) {
//echo "ONE ANALYSISPROP IN ARRAY:<pre>";var_dump($ap);echo "</pre>";
//echo "analysisprop type: [" . $ap->type_id->name . "]<br>";
//echo "analysisprop value: [" . $ap->value . "]<br>";
        if ($ap->type_id->name == 'Analysis Type' && $ap->value == 'genome assembly') {
          $assembly = $af->analysis_id->name;
        }
        else if ($ap->type_id->name == 'Analysis Type' && $ap->value == 'gene model set') {
          $gene_set = $af->analysis_id->name;
        }
      }
    }
    else {
      $ap = $af->analysis_id->analysisprop;
//echo "ONE ANALYSISPROP:<pre>";var_dump($ap);echo "</pre>";
//echo "analysisprop type: [" . $ap->type_id->name . "]<br>";
//echo "analysisprop value: [" . $ap->value . "]<br>";
      if ($ap->type_id->name == 'Analysis Type' && $ap->value == 'genome assembly') {
        $assembly = $af->analysis_id->name;
      }
      else if ($ap->type_id->name == 'Analysis Type' && $ap->value == 'gene model set') {
        $gene_set = $af->analysis_id->name;
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
  
  
  ///////////////////////   SET UP JBROWSE SECTION   ////////////////////////

  $jbrowse_html = '';
  
  if ($feature->type_id->name == "gene") {
    $feature = chado_expand_var($feature, 'table', featureloc, $options);
    $srcfeatures =  $feature->featureloc->feature_id;
  
    while (list(, $srcf) = each($srcfeatures)) {
      // only interested in srcfeature of type 'chromosome' or 'contig'
      if ($srcf->srcfeature_id->type_id->name == 'chromosome'
           || $srcf->srcfeature_id->type_id->name == 'contig') {
        $chrname = $srcf->srcfeature_id->name;
        $chr_id  = $srcf->srcfeature_id->feature_id;
        $chrlen  = $srcf->srcfeature_id->seqlen;
        $start   = $srcf->fmin;
        $end     = $srcf->fmax;
        break;
      } 
      else {
        continue;
      }
    }
//echo "key=$key, data=$data, chr_id=$chr_id, chrname=$chrname, chrlen=$chrlen, start=$start, end=$end, tracks=$tracks<br>";

    if (!$chrname || !$chrlen || !$start || !$end) {
      // Can't create JBrowse object
      $jbrowse_html = 'No browser instance available to display a graphic for this gene.';
    }
    else {
      // Try to get URL from Chado first
      if ($jbrowse_info = getJBrowseURL($chr_id)) {
        $url = $jbrowse_info['url'];
        $chr = $jbrowse_info['chr'];
      }
      else {
        // deprecated: construct URL from alias files
      
        // These files link identifiers in Chado to identifers in JBrowse
        $aliasdir    = 'files/aliasfiles/';
        $data_file   = $aliasdir . 'data_alias.tab';
        $tracks_file = $aliasdir . 'tracks_alias.tab';
        $chr_file    = $aliasdir . 'chr_alias.tab';
  
        // convert alias files to associative arrays:
        $data_arr   = file2arr($data_file);
        $tracks_arr = file2arr($tracks_file);
        $chr_arr    = file2arr($chr_file);

        $key = $feature->organism_id->abbreviation;

        // $data_arr maps the genus and species abbreviation to the dataset name:
        $data   = $data_arr[$key];
  
        // $tracks_arr maps the genus and species abbreviation to the gene model track name:
        $tracks = $tracks_arr[$key];
        
        // The base JBrowse URL
        $url = "$data&tracks=$tracks";
  
        // the LIS chr name is mapped to its jbrowse equivalent 
        $chr = $chr_arr[$chrname];
      }//URL from files (deprecated)
    
      // expand the region by 2k:
      $start = (($start- 2000) < 0) ? 0 : $start = $start- 2000;
      $end = (($end + 2000) > $chrlen) ? $chrlen : $end + 2000;
      $loc = $chr.":".$start."..".$end;

      if ($url && $loc) {
        if ($key ==  "glyma") {   #peu
          // Glycine max JBrowse instance is at Soybase.org
          $url_source = "$url&loc=$loc";
          $jbrowse_html = "
            <div>
              <br>
              If the Soybase.org GBrowse window does not open automatically, click 
              <a href='$url_source' target=_blank>here</a> to see this gene model
              on the soybean genome.
            </div>
            <br>
            <script language='javascript'>
              var re = new RegExp('jbrowse');
              if (window.location.href.match(re)) {
                window.onload = function() { window.open('$url_source'); }
              }
            </script>";
        }//Glycine max
        else {
          $url_source = "$url&loc=$loc";    
          $jbrowse_html = "
            </br>   
            <div>
              <iframe id='frameviewer' frameborder='0' width='100%' height='1000' 
                      scrolling='yes' src='$url_source' name='frameviewer'></iframe>
            </div>";
        }
      }//not Glycine max
    }//JBrowse instance exists
  }//feature is a gene

  
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
     $gene_family_html = "<i> not assigned to a gene family</i>";
  }
  else {
    // Link with uniquename for gene feature (assumes 1 gene family per gene model)
    $url = $gene_family_url . $feature->uniquename;
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
  
  // Description row
  $rows[] = array(
    array(
      'data' => 'Description',
      'header' => TRUE,
      'width' => '20%',
    ),
    $gene_description
  );

  // Protein domains
  $rows[] = array(
    array(
      'data'   => 'Protein domains',
      'header' => true,
      'width'  => '20%', 
    ),
    $domain_html,
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
