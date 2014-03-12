<?php
//
// This script uses the gmap3-API to create the map and the layovers on the map (hero)
//


// Create array for all the locations
$data_array = array();

// Fill the array
if (have_posts()) : while (have_posts()) : the_post();
  $temp = get_field('longitude') . get_field('latitude');
  if ($temp !== "") :
  //if (!empty(get_field('longitude')) && !empty(get_field('latitude'))) : 
    $data_array[] = array(get_the_ID(),get_field('city'),get_field('country'),get_field('longitude'),get_field('latitude'),get_field('arrival_date'),get_field('departure_date'),get_field('accomodation_name'),get_field('accomodation_link'));
  endif;
endwhile; endif;

// Finally redefine the center of focus a tiny bit (looks better)
$new_center = $data_array[0][3] + 0.007;

?>
<script type="text/javascript">
    /* <![CDATA[ */

      $(function(){
      
        $('#fsmap').gmap3({
          map:{
            options:{
              center:[<?php echo $new_center; ?>,<?php echo $data_array[0][4]; ?>],
			  zoom: 12
            },
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
      
      function generateMarkers($this, map, bounds){
        var i,
          bounds = map.getBounds(), 
          southWest = bounds.getSouthWest(),
          northEast = bounds.getNorthEast(),
          lngSpan = Math.abs(northEast.lng() - southWest.lng()),
          latSpan = Math.abs(northEast.lat() - southWest.lat());
<?php
				for ($i = 0; $i < count($data_array); $i++) {
		 			if ($data_array[$i][3] == 1) {
?>
          newMarker($this, "<?php echo $data_array[$i][1]; ?>", <?php echo $data_array[$i][3]; ?>, <?php echo $data_array[$i][4]; ?>, "<h3><?php echo $data_array[$i][1]; ?></h3><h5 class='country'><?php echo $data_array[$i][2]; ?></h5><h5 class='centerme'><strong class='inlineme'>Abflug: </strong><?php echo $data_array[$i][5]; ?></h5>");
<?php
					}
					else {
?>
          newMarker($this, "<?php echo $data_array[$i][1]; ?>", <?php echo $data_array[$i][3]; ?>, <?php echo $data_array[$i][4]; ?>, "<h3><?php echo $data_array[$i][1]; ?></h3><h5 class='country'><?php echo $data_array[$i][2]; ?></h5><h5 class='centerme'><strong><?php echo substr($data_array[$i][5],0,2) . "." . substr($data_array[$i][5],2,2) . "."; ?> - <?php echo substr($data_array[$i][6],0,2) . "." . substr($data_array[$i][6],2,2) . "." . substr($data_array[$i][6],4,4); ?></strong></h5>");
<?php
					}
				}
?>
      }
      
      
      function newMarker($this, i, lat, lng, text){
        $this.gmap3({ 
          marker:{
            latLng: [lat, lng],
            callback: function(marker){
              var $button = $('<span id="button-'+i+'" class="panLink"> ['+i+'] </span>');
              $button
                .click(function(){
                  $this.gmap3("get").panTo(marker.position);
                })
                .css('cursor','pointer');
              $('#panTo').append($button);
            }
          },
          overlay:{
            latLng: [lat, lng],
            options:{
			  content: '<div id="infoOverlaySmall">'+text+'</div>',
              offset:{
                y:-105,
                x:12
              }
            },
          }
        });
      }
	  
    /* ]]> */
    </script>
