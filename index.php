<?php
  if (empty($_GET["route"]) or $_GET["route"]=="true") {
	include("route.php");  
  }
  else {
	  
    get_header(); ?>

      <div class="row">

        <div class="col-sm-8 blog-main">
        
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="blog-post">
            <h2 class="blog-post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
            <p class="blog-post-meta"><?php the_date(); ?> by <a href="<?php get_the_author_link(); ?>"><?php the_author(); ?></a></p>
            <?php the_content(); ?>
          </div>
          <ul class="pager">
            <li><?php previous_posts_link('Newer Posts &raquo;') ?></li>
            <li><?php next_posts_link('&laquo; Older Posts') ?></li>
          </ul>
          <?php endwhile; endif; ?>          

        </div><!-- /.blog-main -->

        <?php get_sidebar(); ?>

      </div><!-- /.row -->

    </div><!-- /.container -->

<?php 
    get_footer();
  }
?>