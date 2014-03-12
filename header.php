<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php
    if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
		echo('<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">');
	}
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Wordpress Travel Blog">
    <meta name="author" content="Robin Binder">

    <title><?php bloginfo('name'); ?> - <?php wp_title(''); ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bs/css/bootstrap.min.css">

    <!-- Custom styles for this template -->  
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>

    <!-- The Top-Menu -->
    <div class="blog-masthead">
      <div class="container">
        <nav class="blog-nav">
          <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
          <?php
		  // Checking if on a static page for active symbol
		  $pages = get_pages(); 
		  $blog_active = TRUE;
		  foreach ( $pages as $page ) {
		      if (trim(wp_title('',FALSE)) == $page->post_title) {
			  	  $blog_active = FALSE;
		      }
		  }
		  if ($blog_active == TRUE){
			  ?>
              <a class="blog-nav-item active" href="<?php echo home_url() . '?route=false'; ?>">Blog</a>
              <?php
		  }
		  else {
			  ?>
              <a class="blog-nav-item" href="<?php echo home_url() . '?route=false'; ?>">Blog</a>
              <?php
		  }
		  // Adding static pages to top menu
		  $pages = get_pages(); 
		  foreach ( $pages as $page ) {
			  if (trim(wp_title('',FALSE)) == $page->post_title) {
				 echo '<a class="blog-nav-item active" href="' . get_page_link( $page->ID ) . '">' . $page->post_title .'</a>'; 
			  }
			  else {
				 echo '<a class="blog-nav-item" href="' . get_page_link( $page->ID ) . '">' . $page->post_title .'</a>'; 
			  }			  
		  }
		  $options = get_option('kb_theme_options');
	      if ($options['facebook'] !== "") {
		  ?>
            <a class="blog-nav-item pull-right" href="<?php echo $options['facebook']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/fb-icon.png" alt="Facebook" /></a>
          <?php
		  }
		  if ($options['twitter'] !== "") {
		  ?>
            <a class="blog-nav-item pull-right" href="<?php echo $options['twitter']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/twitter-icon.png" alt="Twitter" /></a>
          <?php
		  }
		  if ($options['googleplus'] !== "") {
		  ?>
            <a class="blog-nav-item pull-right" href="<?php echo $options['googleplus']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/googleplus-icon.png" alt="GooglePlus" /></a>
          <?php
		  }
		  if ($options['youtube'] !== "") {
		  ?>
            <a class="blog-nav-item pull-right" href="<?php echo $options['youtube']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/youtube-icon.png" alt="Youtube" /></a>
          <?php
		  }
		  if ($options['linkedin'] !== "") {
		  ?>
            <a class="blog-nav-item pull-right" href="<?php echo $options['linkedin']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/linkedin-icon.png" alt="Linkedin" /></a>
          <?php
		  }
		  ?>
        </nav>
      </div>
    </div>
    <!-- End of the Top-Menu -->

    <div class="container">

      <!-- The Hero -->
      <?php
	  if (is_page() == FALSE and get_field('longitude') !== "") {
	  ?>
        <div id="fsmap" class="blog-header">
          <h1 class="blog-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
          <p class="lead blog-description"><?php bloginfo('description'); ?></p>
        </div>
      <?php
	  }
	  ?>
      <!-- End of the Hero -->