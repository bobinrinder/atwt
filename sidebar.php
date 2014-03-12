<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
          <?php
		  // Let's get the theme options...
	      $options = get_option('kb_theme_options');
		  //... and display the aboutme-box if it would have any content
		  if ($options['aboutme'] !== "") {
		  ?>
          <div class="sidebar-module sidebar-module-inset">
            <h4>About Me</h4>
            <p><?php echo $options['aboutme']; ?></p>
          </div>
          <?php
		  }
          ?>
 </div><!-- /.blog-sidebar -->