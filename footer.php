<div class="blog-footer">
      <p>
      <?php
	  // Loading Theme-Option-Content
      $options = get_option('kb_theme_options');
	  // Inserting Copyright-Content (if available)
	  if ($options['copyright'] == "") {
	  ?>
          Wordpress template built by <a href="http://bobinrinder.com" target="_blank">Robin Binder</a>.<br /><br />Credits to <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>, <a href="http://gmap3.net" target="_blank">Gmap3</a>, <a href="http://jquery.com" target="_blank">jQuery</a> and <a href="http://www.advancedcustomfields.com" target="_blank">Elliot Condon</a>.
      <?php
	  }
	  else {
		  echo $options['copyright'];
	  }      
      ?>
      </p>
</div>

    <!-- All the Javascript-Stuff
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> 
    <script src="<?php echo get_template_directory_uri(); ?>/bs/js/bootstrap.min.js"></script>      
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/gmap3.js"></script> 
    <?php 
	// Integrating Google Maps Hero Script
	include("scripts/gmap-usage-small.php"); 
	// Integrating Google Analytics (if available)
	if ($options['analytics'] !== "") {
	  echo $options['analytics'];
	}
	?>
    
  </body>
</html>