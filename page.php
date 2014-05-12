<?php
/*-------------------------------------------------
	BlankPress - Default Page Template
 --------------------------------------------------*/
get_header(); ?>

	<section class="container group" role="main">
    <div class="content">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
        <header>
          <h1><?php the_title(); ?></h1>
          <?php if(is_user_logged_in())  edit_post_link( __( 'edit', BP_DOMAIN ) ); ?>
        </header>
        <?php the_post_thumbnail(); ?>
        <?php  blankpress_post_meta(); ?>
        <div class="post-content group"><?php the_content(); ?></div>
        <section class="comment-section">
          <?php comment_form(); ?>
        </section>
      </article>
      
      <?php endwhile; endif; ?>
    </div>
    
		<?php  get_sidebar(); ?>
	</section>
	
<?php get_footer();