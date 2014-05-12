<?php
/*-------------------------------------------------
	BlankPress - Default 404 Template
 --------------------------------------------------*/
get_header(); ?>

	<section  class="container main-content group" role="main">
	
		<article id="post-404-error"  class="group">
      <header>
        <h1>Page could not be found !</h1>
      </header>
      <div class="post-content group">
        <?php _e( 'It looks like nothing was found at this location. Try searching?', BP_DOMAIN); ?>
      </div>
      
      <?php get_search_form(); ?>
      <p></p>
		</article>
		
	</section><!-- end content -->
	
	<?php // get_sidebar(); ?>
	
<?php get_footer();