<?php
/*-------------------------------------------------
	BlankPress - Default Sidebar Template
 --------------------------------------------------*/
?>
<aside class="sidebar col-xm-12 col-md-4 col-lg-4" role="complementary">
	<?php
	if ( is_active_sidebar( 'sidebar-1' ) ) :
		dynamic_sidebar( 'sidebar-1' );
	endif;
	?>
</aside>