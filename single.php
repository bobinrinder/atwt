<?php get_header(); ?>

      <div class="row">

        <div class="col-sm-8 blog-main">
        
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="blog-post">
          <h2 class="blog-post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
          <p class="blog-post-meta"><?php the_date(); ?> by <a href="<?php get_the_author_link(); ?>"><?php the_author(); ?></a><?php if (get_field('accomodation_name') !== "") : ?> | Accomodation: <?php if (get_field('accomodation_link') !== "") : ?><a href="<?php echo get_field('accomodation_link'); ?>" target="_blank"><?php endif; echo get_field('accomodation_name'); if (get_field('accomodation_link') !== "") : ?></a><?php endif; endif; ?></p>
        <?php the_content(); ?>
        <ul class="pager">
                    <li><?php previous_post_link('%link', '&laquo; Previous Post'); ?></li>
                    <li class="pull-right"><?php next_post_link('%link', 'Next Post &raquo;'); ?></li>
                  </ul>
        </div>
        <?php endwhile; endif; ?>

        </div><!-- /.blog-main -->

        <?php get_sidebar(); ?>

      </div><!-- /.row -->

    </div><!-- /.container -->

<?php get_footer(); ?>