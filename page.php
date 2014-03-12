<?php get_header(); ?>

      <div class="row" style="padding-top: 25px;">

        <div class="col-sm-8 blog-main">
        
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="blog-post">
          <h2 class="blog-post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        <?php the_content(); ?>
        </div>
        <?php endwhile; endif; ?>

        </div><!-- /.blog-main -->

        <?php get_sidebar(); ?>

      </div><!-- /.row -->

    </div><!-- /.container -->

<?php get_footer(); ?>