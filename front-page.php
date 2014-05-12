<?php
/*-------------------------------------------------
	BlankPress - Default Front Page Template
 --------------------------------------------------*/
get_header(); ?>
	<section class="bpslider_wrap" >
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <?php echo do_shortcode("[bp_slider id=18]"); ?>
        </div>
      </div>
    </div>
	</section>
	<section class="container main-content group" role="main">
    <div class="row">
		<div class="col-xm-12 col-md-8 col-lg-8">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
				<header>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
          <?php if(is_user_logged_in())  
                edit_post_link( __( '<span class="icon-text icon-tools">&nbsp;</span>', BP_DOMAIN ) ); ?>
				</header>
        <?php if(has_post_thumbnail()) the_post_thumbnail('image21'); ?>
				<div class="post-content group">
          <?php the_excerpt(); ?>
          <a class="read_more" href='<?php the_permalink(); ?>'><?php _e('READ MORE <span>&raquo;</span>', BP_DOMAIN); ?></a>
        </div>
        <footer>
            <?php  blankpress_post_meta(); ?>
        </footer>
			</article>
			
			<?php endwhile; endif; ?>
			
			<nav class="page-nav">
				<div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
				<div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
			</nav>
		
		</div>
		
		<?php get_sidebar(); ?>
    </div>
	</section>
<?php get_footer();