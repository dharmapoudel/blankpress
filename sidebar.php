<?php
/*-------------------------------------------------
	BlankPress - Default Sidebar Template
 --------------------------------------------------*/
?>
<aside class="sidebar" role="complementary">

	<?php
	if ( is_active_sidebar( 'sidebar-1' ) ) :
		dynamic_sidebar( 'sidebar-1' );
	endif;
	?>
	
</aside>