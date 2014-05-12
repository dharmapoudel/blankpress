<?php
/*-------------------------------------------------
	BlankPress - Register Sidebars and Menus
 --------------------------------------------------*/

// register sidebars and widget areas
add_action( 'widgets_init', 'widgets_init_cb' );

if (!function_exists('widgets_init_cb')){
	function widgets_init_cb() {
		register_sidebar( array(
			'name'          => __( 'Sidebar Widget Area', BP_DOMAIN),
			'id'            => 'sidebar-1',
			'description'   => __( 'Appears in the footer section of the site', BP_DOMAIN),
			'before_widget' => '<article id="%1$s" class="widget group %2$s">',
			'after_widget'  => '</article>',
			'before_title'  => '<header><h1 class="widget-title">',
			'after_title'   => '</h1></header>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer Widget Area', BP_DOMAIN),
			'id'            => 'sidebar-2',
			'description'   => __( 'Appears above the footer.', BP_DOMAIN),
			'before_widget' => '<article id="%1$s" class="widget group %2$s">',
			'after_widget'  => '</article>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}
}

//register menus after theme setup
add_action('after_setup_theme', 'register_blankpress_menus');

if (!function_exists('register_blankpress_menus')){

	function register_blankpress_menus(){
		/* enable custom-menus support for a theme */
		register_nav_menus(array(
			'primary'   => __('Header Menu', BP_DOMAIN), 
			'secondary' => __('Footer Menu', BP_DOMAIN)
		));
	}
}