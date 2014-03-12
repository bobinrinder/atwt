<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="mis">

  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Language" content="en" />
    <meta name="DC.Language" content="en" />
    
    <?php  define('WP_USE_THEMES', false); require('./wp-blog-header.php'); ?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.4.4.min.js"></script>        
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/gmap3.js"></script> 
    
    <?php include("scripts/gmap-usage.php"); ?>      
     
    <link href="<?php echo get_template_directory_uri(); ?>/bs/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/css/custom-styles.css" rel="stylesheet" />
    
    <title><?php bloginfo('name'); ?></title>
       
  </head>
    
  <body>
  
    <!-- This is the actual fullscreen map div -->
    <div id="fsmap" class="gmap3"></div>
  
    <!-- This is where the pan links will appear -->
    <div id="panTo"></div>
  
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