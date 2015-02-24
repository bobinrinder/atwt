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
  global $query_string;
  query_posts ('posts_per_page=-1');
  if (have_posts()) : while (have_posts()) : the_post();
  $temp = get_field('longitude') . get_field('latitude');
  // Only keep the posts with GPS data...
  if ($temp !== "") :
    $post_array[] = array(get_the_ID(),get_field('city'),get_field('country'),get_field('longitude'),get_field('latitude'),get_field('arrival_date'),get_field('departure_date'),get_field('accomodation_name'),get_field('accomodation_link'), get_permalink(get_the_ID()),get_field('photo_link'));
  endif;
  endwhile; endif;
  ?>

  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
  <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/route.js"></script>

  <script>
    (function () {

  // Let's get the data from PHP to JS first... :)

  var posts = [
    <?php
    for ($i = 0; $i < count($post_array); $i++) {
      ?>
      {post_id: <?php echo $post_array[$i][0]; ?>, city: "<?php echo $post_array[$i][1]; ?>", country: "<?php echo $post_array[$i][2]; ?>", latitude: <?php echo $post_array[$i][3]; ?>, longitude: <?php echo $post_array[$i][4]; ?>, arrival_date: "<?php echo substr($post_array[$i][5],0,2).'.'.substr($post_array[$i][5],2,2).'.'.substr($post_array[$i][5],4,4); ?>", departure_date: "<?php echo substr($post_array[$i][6],0,2).'.'.substr($post_array[$i][6],2,2).'.'.substr($post_array[$i][6],4,4); ?>", accomodation_name: "<?php echo $post_array[$i][7]; ?>", accomodation_link: "<?php echo $post_array[$i][8]; ?>", post_link: "<?php echo $post_array[$i][9]; ?>", photo_link: "<?php echo $post_array[$i][10]; ?>"},
    <?php
    }
    ?>
   ];

      // Initialize App
      google.maps.event.addDomListener(window, 'load', function(){
        route.initialize(posts);
      });

    }());

  </script>
<link href="<?php echo get_template_directory_uri(); ?>/bs/css/bootstrap.min.css" rel="stylesheet" />
<link href="<?php echo get_template_directory_uri(); ?>/css/custom-styles.css" rel="stylesheet" />

<title><?php bloginfo('name'); ?></title> 

</head>

<body>

  <!-- This is the actual fullscreen map div -->
  <div id="map-canvas"></div>
  
  <!-- This is where the pan links will appear -->
  <div id="panTo"><input type="button" value="Previous" onClick="route.prev()">&nbsp;&nbsp;<input type="checkbox" checked="checked" onClick="route.toggleRouteVisibility()">&nbsp;&nbsp;<input type="button" value="Next" onClick="route.next()"></div>
  
  <?php
  // Let's get the last modification date of the website...
    global $wpdb;
    $query = $wpdb->get_results( "SELECT post_title, post_modified FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_modified DESC LIMIT 1" );
    foreach ($query as $post) 
    {
      $post_title = $post->post_title;
      $post_date = $post->post_modified;
    }
  // Preparing the date for the echo
    $last_update = substr($post_date, 8, 2) . "." . substr($post_date, 5, 2) . "." . substr($post_date, 0, 4) . " - " . substr($post_date, 11) . " (" . $post_title . ")";
  // And finally the echo... :)
  ?>

  <!-- This is the header -->
  <div id="header" class="hidden-xs hidden-sm">    
    <span id="header-title"><?php bloginfo('name'); ?></span><br /><span id="update-title" class="hidden-xs hidden-sm"><strong>Last Update: </strong><?php echo $last_update; ?></span>
  </div>
  <!-- This is the mobile-header -->
  <div id="header-mobile" class="hidden-md hidden-lg">   
    <span id="header-title-mobile"><?php bloginfo('name'); ?></span><br /><span id="update-title-mobile"><strong>Last Update: </strong><?php echo $last_update; ?></span>
  </div>

  <!-- This is the stats box -->
  <div id="stats" class="hidden-xs hidden-sm">
    <span id="stats-title" class="hidden-xs hidden-sm"></span>
  </div>
  
</body>

</html>