<?php

if($teaser) {
  print render($content);
}
else {
  $node_type = $node->type; ?>
  
  <script type="text/javascript">
    // We do not use Drupal Behaviors because we do not want this
    // code to be executed on AJAX callbacks. This code only needs to 
    // be executed once the page is ready.
    jQuery(document).ready(function($){

      // Hide all but the first data pane 
      $(".tripal-data-pane").hide().filter(":first-child").show();

      $(".tripal_toc_list_item_link").click(function(){
          // When a title in the table of contents is clicked, then 
          // show the corresponding item in the details box
          var id = $(this).attr('id') + "-tripal-data-pane";
          $(".tripal-data-pane").hide().filter("#"+ id).fadeIn('fast');
          // Additionally, set the location.hash for better navigation
          var pane = id.match(/^(\w+)\-tripal\-data\-pane/)[1];
          window.location.hash = 'pane='+pane;
          // Cleanup of previous pane in location.href, if any.
          var url = location.href.replace(location.hash, '');
          var newUrl = url.replace(/[\?\&]?(pane|block)=\w+[\&]?/, '');
          if(newUrl !== url) {
              // trigger a page reload with the cleaned url
              window.location.href = newUrl +'#pane=' + pane;
          }
          return false;
      });

      // If a ?pane= is specified in the URL then we want to show the
      // requested content pane.  #pane= can be used alternately. For
      // previous version of Tripal, ?block=, was used.  We support it
      // here for backwards compatibility
      var pane;
      pane = window.location.href.match(/[\?|\&]pane=(.+?)[\&|\#]/);
      if (pane == null) {
          pane = window.location.href.match(/[\?|\&\#]pane=(.+)/);
      }
      // if we don't have a pane then try the old style ?block=
      if (pane == null) {
          pane = window.location.href.match(/[\?|\&]block=(.+?)[\&|\#]/);
        if (pane == null) {
            pane = window.location.href.match(/[\?|\&]block=(.+)/);
        }
      }
      if(pane != null){
        $(".tripal-data-pane").hide().filter("#"+ pane[1] + "-tripal-data-pane").fadeIn('fast');
      }

      // Remove the 'active' class from the links section, as it doesn't
      // make sense for this layout
      $("a.active").removeClass('active');
    });
  </script>
  
  <div id="tripal_<?php print $node_type?>_contents" class="tripal-contents">
    <table id ="tripal-<?php print $node_type?>-contents-table" class="tripal-contents-table">
      <tr class="tripal-contents-table-tr">
        <td nowrap class="tripal-contents-table-td tripal-contents-table-td-toc"  align="left"><?php
        
          // print the table of contents. It's found in the content array 
          if (array_key_exists('tripal_toc', $content)) {
            print $content['tripal_toc']['#markup'];
          
            // we may want to add the links portion of the contents to the sidebar
            //print render($content['links']);
            
            // remove the table of contents and links so thye doent show up in the 
            // data section when the rest of the $content array is rendered
            unset($content['tripal_toc']);
            unset($content['links']); 
          } ?>

        </td>
        <td class="tripal-contents-table-td-data" align="left" width="100%"> <?php
         
          // print the rendered content 
          print render($content); ?>
        </td>
      </tr>
    </table>
  </div> <?php 
}


