<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <style>
  html, body, #map-canvas {
    height: 100%;
    margin: 0px;
    padding: 0px
  }
  </style>

  <?php  define('WP_USE_THEMES', false); require('./wp-blog-header.php'); ?>

  <?php
    // Create array for all the locations
  $post_array = array();

    // Fill the array
  if (have_posts()) : while (have_posts()) : the_post();
  $temp = get_field('longitude') . get_field('latitude');
      // Only keep the posts with GPS data...
  if ($temp !== "") :
    $post_array[] = array(get_the_ID(),get_field('city'),get_field('country'),get_field('longitude'),get_field('latitude'),get_field('arrival_date'),get_field('departure_date'),get_field('accomodation_name'),get_field('accomodation_link'), get_permalink(get_the_ID()));
  endif;
  endwhile; endif;
  ?>

  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
  <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>

  <script>

  // Let's get the data from PHP to JS first... :)

  var posts = [
    <?php
    for ($i = 0; $i < count($post_array); $i++) {
      ?>
      {post_id: <?php echo $post_array[$i][0]; ?>, city: "<?php echo $post_array[$i][1]; ?>", country: "<?php echo $post_array[$i][2]; ?>", longitude: <?php echo $post_array[$i][3]; ?>, latitude: <?php echo $post_array[$i][4]; ?>, arrival_date: <?php echo $post_array[$i][5]; ?>, departure_date: <?php echo $post_array[$i][6]; ?>, accomodation_name: "<?php echo $post_array[$i][7]; ?>", accomodation_link: "<?php echo $post_array[$i][8]; ?>", post_link: "<?php echo $post_array[$i][9]; ?>", position: new google.maps.LatLng(<?php echo $post_array[$i][3]; ?>, <?php echo $post_array[$i][4]; ?>)},
    <?php
    }
    ?>
   ];

  var flightPlanCoordinates = [
  <?php
  for ($i = 0; $i < count($post_array); $i++) {
    ?>
    new google.maps.LatLng(<?php echo $post_array[$i][3]; ?>, <?php echo $post_array[$i][4]; ?>),
    <?php
  }
  ?>
  ];

  var flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    geodesic: false,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  var zoomlevel = 2;

  var panPressed = false;

  var iCurrentPost = posts.length - 1;

  var map;

  var boxText = document.createElement("div");
  boxText.style.cssText = "border: none; background: url('<?php echo get_template_directory_uri(); ?>/img/slider-bg.png') repeat scroll left top transparent; text-align:left; border-radius: 1em; padding: 5px; color: white; height: 120px; width: 180px";
  boxText.innerHTML = "tesssst";

  var myOptions = {
   content: boxText
   ,disableAutoPan: false
   ,maxWidth: 0
   ,pixelOffset: new google.maps.Size(-140, 0)
   ,zIndex: null
   ,closeBoxURL: ""
   ,boxStyle: { 
     width: "280px"
   }
   ,infoBoxClearance: new google.maps.Size(1, 1)
   ,isHidden: false
   ,pane: "floatPane"
   ,enableEventPropagation: false
 };


 var ib = new InfoBox(myOptions);

 var marker, i;

 function initialize() {
  var mapOptions = {
    zoom: 2,
    center: posts[0].position
  };

  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  flightPath.setMap(map);
  google.maps.event.addListener(map, 'zoom_changed', function() {
    zoomlevel = map.getZoom();
    if (ib) {
   // ib.close();
  //  boxText.innerHTML = "empty";
}
    // map.setCenter(myLatLng);
    // infowindow.setContent('Zoom: ' + zoomLevel);
  });

  google.maps.event.addListener(map, "click", function(event) {
    if (ib) {
      ib.close();
      boxText.innerHTML = "empty";
    } 
  });

  

  for (i = 0; i < posts.length; i++) {  
    marker = new google.maps.Marker({
      position: posts[i].position,
      map: map
    });

    google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
      return function() {

        if (panPressed === true) {
          i = iCurrentPost;
        }

        if (boxText.innerHTML !== posts[i].country) {
          if (ib) {
            ib.close(); 
            boxText.innerHTML = "empty";
          }
          if (i === (posts.length -1)) {
            boxText.innerHTML = "<h3>"+posts[i].city+"</h3><h5 class='country'>"+posts[i].country+"</h5><h5 class='centerme'><strong class='inlineme'>Departure: </strong>"+posts[i].arrival_date+"</h5>";
          }
          else {
            boxText.innerHTML = "<h3>"+posts[i].city+"</h3><h5 class='country'>"+posts[i].country+"</h5><h5 class='centerme'><strong>"+posts[i].arrival_date+" to "+posts[i].departure_date+"</strong></h5><div class='centerme'><a class='btn btn-primary btn-sm' href='"+posts[i].post_link+"' target='_self' class='centerme'>Post</a>&nbsp;&nbsp;&nbsp;<a class='btn btn-primary btn-sm' href='"+posts[i].accomodation_link+"' target='_blank' class='centerme'>Accomodation</a></div>";
          }
          ib = new InfoBox(myOptions);
          ib.open(map, marker);
        }
        panPressed = false;
      }
    })(marker, i));

google.maps.event.addListener(marker, 'click', (function(marker, i) {
  return function() {
    map.panTo(flightPlanCoordinates[i]);
  }
})(marker, i));

}
}



function drop() {
  for (var i = 0; i < neighborhoods.length; i++) {
    setTimeout(function() {
      addMarker();  
    }, i * 200);
  }
}

function addMarker() {
  markers.push(new google.maps.Marker({
    position: neighborhoods[iterator],
    map: map,
    draggable: false,
    animation: google.maps.Animation.DROP
  }));
  iterator++;
}

function toggleRouteVisibility() {
  flightPath.getVisible() ? flightPath.setVisible(false) : flightPath.setVisible(true);
}

function panToNextPost() {
  if (iCurrentPost === 0) {
    iCurrentPost = posts.length - 1;
  }
  else {
    iCurrentPost--;
  }
  map.panTo(flightPlanCoordinates[iCurrentPost]);
  panPressed = true;
  marker = flightPlanCoordinates[iCurrentPost];
  google.maps.event.trigger(marker, 'mouseover', iCurrentPost);
}

function panToPreviousPost() {
  if (iCurrentPost === posts.length - 1) {
    iCurrentPost = 0;
  }
  else {
    iCurrentPost++;
  }
  map.panTo(flightPlanCoordinates[iCurrentPost]);
  panPressed = true;
  marker = flightPlanCoordinates[iCurrentPost];
  google.maps.event.trigger(marker, 'mouseover', iCurrentPost);
}

google.maps.event.addDomListener(window, 'load', initialize);



</script>  

<link href="<?php echo get_template_directory_uri(); ?>/bs/css/bootstrap.min.css" rel="stylesheet" />
<link href="<?php echo get_template_directory_uri(); ?>/css/custom-styles.css" rel="stylesheet" />

<title><?php bloginfo('name'); ?></title> 

</head>

<body>

  <!-- This is the actual fullscreen map div -->
  <div id="map-canvas"></div>
  
  <!-- This is where the pan links will appear -->
  <div id="panTo"><input type="button" value="Previous" onClick="panToPreviousPost()">&nbsp;&nbsp;<input type="checkbox" checked="checked" onClick="toggleRouteVisibility()">&nbsp;&nbsp;<input type="button" value="Next" onClick="panToNextPost()"></div>
  
  <!-- This is the header -->
  <div id="header">
    <?php
  // Let's get the last modification date of the website...
    global $wpdb;
    $query = $wpdb->get_results( "SELECT post_modified FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_modified DESC LIMIT 1" );
    foreach ($query as $post) 
    {
      $temp = $post->post_modified;
    }
  // Preparing the date for the echo
    $last_update = substr($temp, 8, 2) . "." . substr($temp, 5, 2) . "." . substr($temp, 0, 4) . " @ " . substr($temp, 11);
  // And finally the echo... :)
    ?>
    <span id="header-title"><?php bloginfo('name'); ?></span><br /><span id="update-title">Last Update: <?php echo $last_update; ?></span>
  </div>
  
</body>

</html>