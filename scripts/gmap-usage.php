<?php
//
// This script uses the gmap3-API to create the map and the layovers on the map (fullscreen)
//


// Create array for all the locations
$data_array = array();

// Fill the array
if (have_posts()) : while (have_posts()) : the_post();
  $temp = get_field('longitude') . get_field('latitude');
  // Only keep the posts with GPS data...
  if ($temp !== "") :
    $data_array[] = array(get_the_ID(),get_field('city'),get_field('country'),get_field('longitude'),get_field('latitude'),get_field('arrival_date'),get_field('departure_date'),get_field('accomodation_name'),get_field('accomodation_link'));
  endif;
endwhile; endif;

?>
<script type="text/javascript">
    /* <![CDATA[ */

      $(function(){
      
        $('#fsmap').gmap3({
          map:{
            options:{
			  /* Setting Center of the map and the Zoom-Level */
              center:[<?php echo $data_array[0][3]; ?>,<?php echo $data_array[0][4]; ?>],
			  zoom: 12
            },
			/* Events for generating the markers and the layovers */
            callback: function(map){
              if (map.getBounds()){
                generateMarkers($(this), map);
              } else {
                $(this).gmap3({
                  map:{
                    onces:{
                      bounds_changed: function(map){
                        generateMarkers($(this), map);
                      }
                    }
                  }
                });
              }
            }
          },
		  /* Paint the lines between the cities */
		  polyline:{
				options:{
				strokeColor: "#FF0000",
				strokeOpacity: 1.0,
				strokeWeight: 2,
				path:[
				<?php
				for ($i = 0; $i < count($data_array); $i++) {
					if ($i == count($data_array) - 1) {
						echo "[" . $data_array[$i][3] . "," . $data_array[$i][4] . "]";
					}
					else {
						echo "[" . $data_array[$i][3] . "," . $data_array[$i][4] . "],";
					}
				}
				?>
				] 
				}
		  }
        });
        
      });
      
	  /* Function defines the markers and the layovers */
      function generateMarkers($this, map, bounds){
        var i,
          bounds = map.getBounds(), 
          southWest = bounds.getSouthWest(),
          northEast = bounds.getNorthEast(),
          lngSpan = Math.abs(northEast.lng() - southWest.lng()),
          latSpan = Math.abs(northEast.lat() - southWest.lat());
<?php
				for ($i = 0; $i < count($data_array); $i++) {
		 			if ($i == (count($data_array) - 1)) {
?>
          /* Starting-Marker */
          newMarker($this, "<?php echo $data_array[$i][1]; ?>", <?php echo $data_array[$i][3]; ?>, <?php echo $data_array[$i][4]; ?>, "<h3><?php echo $data_array[$i][1]; ?></h3><h5 class='country'><?php echo $data_array[$i][2]; ?></h5><h5 class='centerme'><strong class='inlineme'>Departure: </strong><?php echo substr($data_array[$i][5],0,2) . "." . substr($data_array[$i][5],2,2) . "." . substr($data_array[$i][5],4,4); ?></h5>");
<?php
					}
					else {
?>
           /* All other markers */
          newMarker($this, "<?php echo $data_array[$i][1]; ?>", <?php echo $data_array[$i][3]; ?>, <?php echo $data_array[$i][4]; ?>, "<h3><?php echo $data_array[$i][1]; ?></h3><h5 class='country'><?php echo $data_array[$i][2]; ?></h5><h5 class='centerme'><strong><?php echo substr($data_array[$i][5],0,2) . "." . substr($data_array[$i][5],2,2) . "."; ?> - <?php echo substr($data_array[$i][6],0,2) . "." . substr($data_array[$i][6],2,2) . "." . substr($data_array[$i][6],4,4); ?></strong></h5><div class='centerme'><a class='btn btn-primary btn-sm' href='<?php echo get_permalink( $data_array[$i][0] );  ?>' target='_self' class='centerme'>Post</a><?php if ($data_array[$i][8] !== "") : ?>&nbsp;&nbsp;&nbsp;<a class='btn btn-primary btn-sm' href='<?php echo $data_array[$i][8]; ?>' target='_blank' class='centerme'>Accomodation</a><?php endif; ?></div>");
<?php
					}
				}
?>
      }
      
      /* Function actually paints the marker and the layovers */
      function newMarker($this, i, lat, lng, text){
        $this.gmap3({ 
          marker:{
            latLng: [lat, lng],
            callback: function(marker){
			  /* Creates the pan-Links */
              var $button = $('<span id="button-'+i+'" class="panLink">&nbsp;['+i+']&nbsp;</span>');
              $button
                .click(function(){
                  $this.gmap3("get").panTo(marker.position);
                })
                .css('cursor','pointer');
              $('#panTo').append($button);
            }
          },
          overlay:{
			/* Creates the overlay */
            latLng: [lat, lng],
            options:{
			  content: '<div id="infoOverlay">'+text+'</div>',
              offset:{
                y:-145,
                x:12
              }
            },
          }
        });
      }
    /* ]]> */
    </script>
