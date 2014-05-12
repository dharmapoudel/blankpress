<?php
/*-------------------------------------------------
	BlankPress - Default Blog Template
	Template Name: HomePage Template
 --------------------------------------------------*/
get_header(); ?>
	<section class="container group">
		<?php echo do_shortcode('[bp_slider]'); ?>
	</section>
	<section class="container group" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
			<header>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<time datetime="<?php the_time( 'Y-m-d' ) ?>" pubdate><?php the_time( 'F j, Y' ) ?></time>
			</header>
			<?php the_content(); ?>
		</article>
		
		<?php endwhile; endif; ?>

	</section>
	
	<?php //get_sidebar(); ?>

<?php get_footer();