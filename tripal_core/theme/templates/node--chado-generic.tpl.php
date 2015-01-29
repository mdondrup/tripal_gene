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
 
      // If a ?pane= is specified in the URL then we want to show the
      // requested content pane. For previous version of Tripal,
      // ?block=, was used.  We support it here for backwards
      // compatibility

      var pane;

      pane = window.location.href.match(/[\?|\&]pane=(.+?)[\&|\#]/);
      if (pane == null) {
          pane = window.location.href.match(/[\?|\&]pane=(.+)/);
      }
      // if we don't have a pane then try the old style ?block=
      if (pane == null) {
          pane = window.location.href.match(/[\?|\&]block=(.+?)[\&|\#]/);
        if (pane == null) {
            pane = window.location.href.match(/[\?|\&]block=(.+)/);
        }
      }

      // When a title in the table of contents is clicked, then 
      // show the corresponding item in the details box 
      $(".tripal_toc_list_item_link").click(function(){
        var id = $(this).attr('id') + "-tripal-data-pane";
        var pane_id = $(this).attr('id');        

        // the click event occurs on a page that does have previously set pane value in the url:
        if(pane != null){         
            window.location.assign(window.location.href.replace("block=".concat(pane[1]), "pane=".concat(pane_id))
            .replace("pane=".concat(pane[1]), "pane=".concat(pane_id)));
            return false;
        } // the click event occurs on a page that does NOT ave previously set pane value in the url:  
        else{  
          var add_pane = window.location.href.match(/\?/) ? "&" : "?";
          window.location.assign(window.location.href.concat(add_pane).concat("pane=").concat(pane_id));         
          return false;
        }
      });


      // this code gets executed when the page is accessed via .tripal_toc_list_item_link click event:      
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


