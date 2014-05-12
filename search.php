<?php
/*-------------------------------------------------
	BlankPress - Default search Template
 --------------------------------------------------*/
get_header(); ?>
	
	<section  class="container group" role="main">
    <div class="content">
      <h1 class="search-page-title">Search results for <strong>'<?php echo get_search_query(); ?>'</strong></h1>
      
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
        <header>
          <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
          <time datetime="<?php the_time( 'Y-m-d' ) ?>" pubdate><?php the_time( 'F j, Y' ) ?></time>
        </header>
        <?php the_excerpt(); ?>
      </article>
      
      <?php endwhile; endif; ?>
      
      <div class="page-nav">
        <div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
        <div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
      </div>
    </div>
    <?php get_sidebar(); ?>
	</section>
  
<?php get_footer();