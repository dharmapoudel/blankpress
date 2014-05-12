<?php
/*-------------------------------------------------
	BlankPress - Default Category Template
 --------------------------------------------------*/
get_header(); ?>
	
	<section class="container group archive-header" >
    <h1><?php _e('Archive for: ', BP_DOMAIN); ?><strong><?php single_cat_title(); ?></strong></h1>
  </section>
	<section class="container group" role="main">
    <div class="content">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
				<header>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
          <?php if(is_user_logged_in())  edit_post_link( __( 'edit', BP_DOMAIN ) ); ?>
				</header>
        <?php if(has_post_thumbnail()) the_post_thumbnail('image21'); ?>
				<div class="post-content group">
          <?php the_excerpt(); ?>
          <a href='<?php the_permalink(); ?>'><?php _e('READ MORE <span>&raquo;</span>', BP_DOMAIN); ?></a>
        </div>
        <footer>
            <?php  blankpress_post_meta(); ?>
        </footer>
			</article>
      
      <?php endwhile; ?>
      
      <nav class="page-nav">
        <div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
        <div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
      </nav>
      
      <?php endif; ?>
    </div>
    <?php get_sidebar(); ?>
	</section>

<?php get_footer();