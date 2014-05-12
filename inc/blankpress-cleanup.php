<?php
/*-------------------------------------------------
	BlankPress - Cleanup WordPress mess
 --------------------------------------------------*/

/* remove wordpress junks  */
remove_action( 'wp_head', 'wp_generator' );			//  wordpress version from header 
remove_action( 'wp_head', 'rsd_link' );				// link to Really Simple Discovery service endpoint
remove_action( 'wp_head', 'wlwmanifest_link' );		// link to Windows Live Writer manifest file

remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds

remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'start_post_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action( 'template_redirect', 'wp_shortlink_header', 11 ); // Remove WordPress Shortlinks from HTTP Headers


// Hide Help tab
add_action('admin_head', 'hide_help');

function hide_help() {
    $screen = get_current_screen();
    //echo "<pre>"; print_r($screen); echo "</pre>";
    if($screen->id ==='dashboard'){
        $screen->remove_help_tabs();
    }  
}

//remove update nag
add_action('admin_menu','wphidenag');

function wphidenag() {
    remove_action( 'admin_notices', 'update_nag', 3);
}

//remove  WordPress Welcome Panel
remove_action( 'welcome_panel', 'wp_welcome_panel' );

//remove update submenu under Dashboard
add_action('admin_init', 'remove_update_submenu');

function remove_update_submenu(){
    global $submenu;
    unset($submenu['index.php'][10]);
}

//remove update browser nag
add_action( 'admin_init', 'wphidebrowsernag' );

function wphidebrowsernag() {
    $key = md5( $_SERVER['HTTP_USER_AGENT'] );
    add_filter( 'site_transient_browser_' . $key, '__return_null' );
}

//remove dashboard widgets
add_action('admin_init', 'remove_dashboard_widgets');

function remove_dashboard_widgets() {

    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); // Quick Draft
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // WordPress News
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity

    //remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Comments Widget
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );  // Incoming Links Widget
	//remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );         // Plugins Widget
}

// Remove version numbers from CSS and JS src
add_filter( 'style_loader_src', 'wp_remove_versions', 9999 );
add_filter( 'script_loader_src', 'wp_remove_versions', 9999 );

function wp_remove_versions( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

// Move JavaScripts from Header to Footer to Speed Page Loading
remove_action('wp_head', 'wp_print_scripts');
//remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'wp_enqueue_scripts', 1);
add_action('wp_footer', 'wp_print_scripts', 5);
add_action('wp_footer', 'wp_enqueue_scripts', 2);
//add_action('wp_footer', 'wp_print_head_scripts', 5);


/*  remove default styles set by WordPress recent comments widget */
add_action( 'widgets_init', 'remove_recent_comments_style' );

if (!function_exists('remove_recent_comments_style')){
	function remove_recent_comments_style() {
		global $wp_widget_factory;
		if ( isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) )
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}

/* remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail */
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // remove width and height dynamic attributes to post images

if ( ! function_exists( 'remove_thumbnail_dimensions' ) ) {
	function remove_thumbnail_dimensions( $html ){
		$html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
		return $html;
	}
}

/*  remove WordPress generated category and tag atributes for W3C validation */
add_filter('wp_list_categories', 'remove_category_rel');
add_filter('the_category', 'remove_category_rel');

if(!function_exists('remove_category_rel')){
	function remove_category_rel($output) {
		$output = str_replace(' rel="category tag"', '', $output);
		return $output;
	}
}

// remove Admin bar
//add_filter('show_admin_bar', 'show_admin_bar_cb'); 

if ( ! function_exists( 'show_admin_bar_cb' ) ) {
	function show_admin_bar_cb(){
		return false;
	}
}

//custom login error message
add_filter('login_errors', 'custom_error_msg');

if ( ! function_exists( 'custom_error_msg' ) ) {
	function custom_error_msg(){
		return "Invalid!";
	}
}