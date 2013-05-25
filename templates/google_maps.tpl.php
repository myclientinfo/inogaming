<?php
if($_SERVER['host_name']=='dev.gamersevents.com') $api_key = 'ABQIAAAA90if-tj66DmguhQCYohDNRRMNh9a7KV0sUzcPKtkTt1nyI0CxxT24qH8hfFRnuMxhkpCzBpwtTKQ6g';
else $api_key = 'ABQIAAAA90if-tj66DmguhQCYohDNRS7pXaDllinQRvc-QxX_JuK1dI9ShSpp1cAuNfc5G8cdtNalmt431fy1A';
?>

<!-- ++Begin Map Search Control Wizard Generated Code++ -->
  <!--
  // Created with a Google AJAX Search Wizard
  // http://code.google.com/apis/ajaxsearch/wizards.html
  -->

  <!--
  // The Following div element will end up holding the map search control.
  // You can place this anywhere on your page
  -->
  <div id="mapsearch">
    <span style="color:#676767;font-size:11px;margin:10px;padding:4px;">Loading...</span>
  </div>

  <!-- Maps Api, Ajax Search Api and Stylesheet
  // Note: If you are already using the Maps API then do not include it again
  //       If you are already using the AJAX Search API, then do not include it
  //       or its stylesheet again
  //
  // The Key Embedded in the following script tags is designed to work with
  // the following site:
  // http://www.emsaustralia.com.au
  -->
  <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $api_key ?>"
    type="text/javascript"></script>
  <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&source=uds-msw&key=<?php echo $api_key ?>"
    type="text/javascript"></script>
   <!--
   // test key for matt's virtualhost
   <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $api_key ?>"
    type="text/javascript"></script>
  <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&source=uds-msw&key=<?php echo $api_key ?>"
    type="text/javascript"></script>-->
  <style type="text/css">
    @import url("http://www.google.com/uds/css/gsearch.css");
  </style>

  <!-- Map Search Control and Stylesheet -->
  <script type="text/javascript">
    window._uds_msw_donotrepair = true;
  </script>
  <script src="http://www.google.com/uds/solutions/mapsearch/gsmapsearch.js?mode=new"
    type="text/javascript"></script>
  <style type="text/css">
    @import url("http://www.google.com/uds/solutions/mapsearch/gsmapsearch.css");
  </style>

  <style type="text/css">
    .gsmsc-mapDiv {
      height : 275px;
    }

    .gsmsc-idleMapDiv {
      height : 275px;
    }

    #mapsearch {
      width : 455px;
      margin: 10px;
      padding: 4px;
    }
  </style>
  <script type="text/javascript">
    function LoadMapSearchControl() {

      var options = {
            zoomControl : GSmapSearchControl.ZOOM_CONTROL_ENABLE_ALL,
            title : "<?php echo $instance_array['instance_title'] ?>",
            url : "<?php echo $data_array['website'] ?>",
            idleMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM-2,
            activeMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM-2
            }

      new GSmapSearchControl(
            document.getElementById("mapsearch"),
            "<?php echo $instance_array['map'] ?>",
            options
            );

    }
    // arrange for this function to be called during body.onload
    // event processing
    GSearch.setOnLoadCallback(LoadMapSearchControl);
  </script>
<!-- ++End Map Search Control Wizard Generated Code++ -->
