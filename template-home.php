<?php
/*-------------------------------------------------
	BlankPress - Default Blog Template
	Template Name: HomePage Template
 --------------------------------------------------*/
get_header(); ?>
	<section class="container" role="main">
		<?php echo do_shortcode('[bp_slider]'); ?>
	</section>
	<section class="container" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
			<header>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<time datetime="<?php the_time( 'Y-m-d' ) ?>" pubdate><?php the_time( 'F j, Y' ) ?></time>
			</header>
			<?php the_excerpt(); ?>
		</article>
		
		<?php endwhile; endif; ?>
		
		<div class="page-nav">
			<div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
			<div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
		</div>

	</section><!-- end content -->
	
	<?php //get_sidebar(); ?>

<?php get_footer();