<?php
/**
* BlankPress Theme Framework 1.1
* Date Updated: 2013-8-23
* Author: Dharma Poudel (@rogercomred)
*
* - Sets up the theme 
* - Provide some helper functions
* - Add shortcodes
* - Register Custom Post type
*/

//define some constants
if(! defined('THEME_NAME'))	define('THEME_NAME', 'BP'); // theme name
if(! defined('TEXT_DOMAIN')) define('TEXT_DOMAIN', 'blankpress'); // theme textdomain
 
// add the admin option
require_once('admin/theme-option.php');

// after theme set up : do some initialization
add_action('after_setup_theme', 'blankpress_setup');

if (!function_exists('blankpress_setup')){
	function blankpress_setup() {

		// Global content width (set the value based on the theme design)
		global $content_width;
		if (!isset($content_width)) $content_width = 650;
		
		//theme textdomain for translation
		load_theme_textdomain( TEXT_DOMAIN, get_template_directory() . '/languages');
		
		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
		require_once( $locale_file );
				
		//callback for custom TinyMCE editor stylesheets. (editor-style.css)
		add_editor_style();
		//enable post and comment RSS feed links to head.
		add_theme_support('automatic-feed-links');
		//enable post-thumbnail support for a theme
		
		add_theme_support('post-thumbnails');
		//set_post_thumbnail_size( 604, 270, true );	// set custom image size for featured images
		// Custom image sizes
		add_image_size( 'featured', 700, 400, true); // Set the size of Featured Image
		add_image_size('thumbnail-bw', 300, 0, false); // generate black and white thumbnail
		
		/* enable custom background color and image support for a theme */
		add_theme_support( 'custom-background', array(
			'default-color' => '',
		) );
		
		/* enable custom header color and image support for a theme */
		add_theme_support( 'custom-header', array(
			'default-text-color'     => 'FE6E41', 	// text color 
			'default-image'          => '',			// and image (empty to use none).
			'height'                 => 152,		// Set height and
			'width'                  => 960,		// set width,
			'max-width'              => 2000,		//  with a maximum value for the width.
			'flex-height'            => true,		// Support flexible height 
			'flex-width'             => true 		// and width.
		) ); 
		
		/* enable custom-menus support for a theme */
		register_nav_menus(array(
			'primary'   => __('Primary Navigation', TEXT_DOMAIN), 
			'secondary' => __('Secondary Navigation', TEXT_DOMAIN),
			'tertiary' => __('Tertiary Navigation', TEXT_DOMAIN)
		));
		
		if(get_option('page_on_front')=='0' && get_option('show_on_front')=='posts' ){
			/* automatically set homepage */
			
			//$page = get_page_by_title( 'Home' );
			//if ( is_page($page->ID) ) return ;
			
			// Create homepage
			/* $homepage = array(
				'post_type'    => 'page',
				'post_title'    => 'Home',
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_author'   => 1
			);  */
			// Insert the post into the database
			//$homepage_id =  wp_insert_post( $homepage );
			// set this page as homepage
			//update_option('show_on_front', 'page');
			//update_option('page_on_front', $homepage_id);
			//set the page template assuming you have defined template on your-template-filename.php
			//update_post_meta($homepage_id, '_wp_page_template', 'your-template-filename.php');
		}
	}
}


/* generate black and white thumbnail of an image */
/* http://ottopress.com/2011/customizing-wordpress-images/ */
/* http://php.net/manual/en/function.imagefilter.php */
add_filter('wp_generate_attachment_metadata', 'bw_images_filter');
function bw_images_filter($meta) {
	$file = wp_upload_dir();
	$file = trailingslashit($file['path']).$meta['sizes']['thumbnail-bw']['file'];
	list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
	$image = wp_load_image($file);
	imagefilter($image, IMG_FILTER_GRAYSCALE);
	//imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
	switch ($orig_type) {
		case IMAGETYPE_GIF:
			$file = str_replace(".gif", "-bw.gif", $file);
			imagegif( $image, $file );
			break;
		case IMAGETYPE_PNG:
			$file = str_replace(".png", "-bw.png", $file);
			imagepng( $image, $file );
			break;
		case IMAGETYPE_JPEG:
			$file = str_replace(".jpg", "-bw.jpg", $file);
			imagejpeg( $image, $file );
			break;
	}
	return $meta;
}

/* remove wordpress junks  */
remove_action( 'wp_head', 'wp_generator' );			//  wordpress version from header 
remove_action( 'wp_head', 'rsd_link' );				// link to Really Simple Discovery service endpoint
remove_action( 'wp_head', 'wlwmanifest_link' );		// link to Windows Live Writer manifest file

remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'start_post_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);


/*  enqueue modernizr only, on head for front end. */
add_action( 
	'wp_enqueue_scripts',		// tag (string required none) - name of the action to which the function_to_add is hooked
	'enqueue_modernizer_cb',	// function_to_add (callback required none) - name of the function to be hooked
	10							// priority (int optional 10) - order in which fxn associated with particular action are executed, lower number = earlier execution
);

if (!function_exists('enqueue_modernizer_cb')){
	function enqueue_modernizer_cb() {
		// register modernizr 
		wp_register_script(
			'modernizr', 	// handle (string required none)- unique id use ? to pass query string
			get_template_directory_uri() . '/js/modernizr.js',  //src (string optional false)- URL to the script
			false, 			// deps (array optional array()) - array of the handles of all the scripts that this script depends on
			'2.0.6', 		// ver(string optional false) - script version number. if no version specified or set to false wp adds its current version , if null no version number is added
			false			// in_footer (boolean optional false) - whether to place script in footer, set true to place scripts in footer.
		); 
		// enqueue modernizr
		wp_enqueue_script('modernizr');
	}
}

/*  enqueue styles on head for front end. */
add_action( 'wp_enqueue_scripts', 'enqueue_styles_cb', 11 );

if (!function_exists('enqueue_styles_cb')){
	function enqueue_styles_cb() {
		// register stylesheet files
		wp_register_style('Raleway', 'http://fonts.googleapis.com/css?family=Raleway', array(), '1.0', 'all');
		wp_register_style( 'flexslider', get_template_directory_uri(). '/css/flexslider.css', array(), '1.0', 'all');
		wp_register_style( 'fancybox', get_template_directory_uri(). '/css/jquery.fancybox.css', array(), '1.0', 'all' );
		
		// first load other stylesheets at once
		wp_enqueue_style( array('Raleway','flexslider', 'fancybox'));
		
		// then load our main stylesheet
		wp_enqueue_style( 'blankpress-style', get_stylesheet_uri() );

		// finally load the Internet Explorer specific stylesheet.
		//global $wp_styles;
		//wp_enqueue_style( 'blankpress-ie', get_template_directory_uri() . '/css/ie.css', array( 'blankpress-style' ), '20130213' );
		//$wp_styles->add_data( 'blankpress-ie', 'conditional', 'lt IE 9' );
	}
}

/*  enqueue scripts at the end for front end. */
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_cb' );

if (!function_exists('enqueue_scripts_cb')){
	function enqueue_scripts_cb() {
		if (!is_admin()) {
			// add JavaScript to pages with the comment form to enable threaded comments
			if (is_singular() AND comments_open() AND (get_option('thread_comments') === 1)) {
				wp_enqueue_script('comment-reply');
			}

			wp_deregister_script( 'jquery' ); // Load Local Jquery (development environment)  or from GoogleApis (production environment)
			//wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, '1.8.2', true);
			wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery-1.8.2.min.js', false, '1.8.2', true);
			wp_enqueue_script( 'jquery' );
			
			// register JavaScript files
			wp_register_script( 'plugins', get_template_directory_uri() . '/js/plugins.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'fancybox', get_template_directory_uri() . '/js/jquery.fancybox.js', array( 'jquery' ), '2.0.6', true );
			wp_register_script( 'main-js', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'jQueryNav', get_template_directory_uri() . '/js/jQueryNav.js', array( 'jquery' ), '1.0', true );
			
			//load all scripts at once
			wp_enqueue_script( array('plugins', 'flexslider', 'fancybox', 'jQueryNav', 'main-js' ));
			
			//load fancybox only on gallery page
			//if ( is_page('gallery')) wp_enqueue_script( array('fancybox'));
			
			wp_localize_script('jquery','bp', array( 'adminUrl' => admin_url("admin-ajax.php") ));
		}
	}
}

/* add meta tags on the header */
add_action( 'theme_meta', 'meta_cb' );

if (!function_exists('meta_cb')){
	function meta_cb(){
		echo '<meta charset="'.get_bloginfo('charset').'">';
		echo '<meta name="author" content="BlankPress">';
		echo '<meta name="description" content="'.get_bloginfo('description').'">';
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		echo '<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
		//echo "<meta name='viewport' content='width=1024' />"; // for testing purpose
	}
}
//load meta in header section by calling theme_meta()
function theme_meta(){
	do_action('theme_meta');
}

// add favicon to the head
add_action('wp_head', 'favicons_cb');
add_action('admin_head', 'favicons_cb'); // also add favicon on admin side

if (!function_exists('favicons_cb')){
	function favicons_cb() {
		$favicon_url = get_option(TEXT_DOMAIN.'_favicon_url', true);
		if(!empty($favicon_url)){
			echo '<link rel="shortcut icon" href="'.$favicon_url.'" type="image/x-icon" />'; 
		}
	}
}

// add web clip icons to the head
add_action('wp_head', 'webclip_icons_cb');

if (!function_exists('webclip_icons_cb')){
	function webclip_icons_cb() {
		$webclip_iconsize = get_option(TEXT_DOMAIN.'_webclip_iconsize', true);
		if(!empty($webclip_iconsize)){
		$webclip_iconurl = get_option(TEXT_DOMAIN.'_webclip_iconurl', true);
			for($i=0; $i< count($webclip_iconsize); $i++ ){ 
				echo '<link rel="apple-touch-icon-precomposed" sizes="'.$webclip_iconsize[$i].'" href="'.$webclip_iconurl[$i].'">'; 
			}
		}
	}
}

/* create custom hook to display site logo */
add_action('theme_logo', 'theme_logo_cb');

if (!function_exists('theme_logo_cb')){
	function theme_logo_cb() {
		$logo_url = (get_option(TEXT_DOMAIN.'_logo_url')=='')? get_template_directory_uri().'/img/logo.png' : get_option(TEXT_DOMAIN.'_logo_url');
		$alt_text = (get_option(TEXT_DOMAIN.'_logo_alt_text') =='')? get_bloginfo('name') : get_option(TEXT_DOMAIN.'_logo_alt_text'); ;
		echo  "<img src='".$logo_url."' alt='".$alt_text."' />";
	}
}

//load logo in header section by calling theme_logo()
function theme_logo(){
	do_action('theme_logo');
}

/* add custom code to the header */
//add_action('wp_head', 'blankpress_header_code');
if (!function_exists('blankpress_header_code')){
	function blankpress_header_code(){
		$header_code = get_option(TEXT_DOMAIN.'_header_code', true);
		if(!empty($header_code)){
			echo stripslashes($header_code);
		}
	}	
}

/* add custom code to the footer */
//add_action('wp_footer', 'blankpress_footer_code');
if (!function_exists('blankpress_footer_code')){
	function blankpress_footer_code(){
		$footer_code = get_option(TEXT_DOMAIN.'_footer_code', true);
		if(!empty($footer_code)){
			echo stripslashes($footer_code);
		}
	}	
}


// register sidebars and widget areas
add_action( 'widgets_init', 'widgets_init_cb' );

if (!function_exists('widgets_init_cb')){
	function widgets_init_cb() {
		register_sidebar( array(
			'name'          => __( 'Main Widget Area', TEXT_DOMAIN),
			'id'            => 'sidebar-1',
			'description'   => __( 'Appears in the footer section of the site', TEXT_DOMAIN),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		register_sidebar( array(
			'name'          => __( 'Secondary Widget Area', TEXT_DOMAIN),
			'id'            => 'sidebar-2',
			'description'   => __( 'Appears on posts and pages in the sidebar.', TEXT_DOMAIN),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}


/*  remove default styles set by WordPress recent comments widget */
add_action( 'widgets_init', 'remove_recent_comments_style' );

if (!function_exists('remove_recent_comments_style')){
	function remove_recent_comments_style() {
		global $wp_widget_factory;
		if ( isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) )
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}


/* dynamically add page slug to body class depending on page user is browsing */

if (!function_exists('dynamic_body_classes')){
	function dynamic_body_classes() {
	
		global $post;
		$dynamic_class = $post->post_name;
		if ( ! empty( $post->post_parent ) )
		$dynamic_class .= " ".get_post($post->post_parent)->post_name;
		$default_classes = implode(" ", get_body_class( false ));
	
		if ( is_front_page() ) {
			echo 'id="home" class="frontpage ' . $default_classes . '"';
		} elseif ( is_home() ) {
			echo 'id="blog" class=" homepage interior ' . $dynamic_class . ' ' . $default_classes . '"';
		} elseif ( is_single() ) {
			echo 'id="blog" class="interior ' . $dynamic_class . ' ' . $default_classes . '"';
		} elseif ( is_archive() ) {
			echo 'id="blog" class="interior ' . $dynamic_class . ' ' . $default_classes . '"';
		} elseif ( is_search() ) {
			echo 'id="search" class="interior ' . $dynamic_class . ' ' . $default_classes . '"';
		} else {
			echo 'id="' . $post->post_name . '" class="interior ' . $dynamic_class . ' ' . $default_classes . '"';
		}
	}
}

/* Filter wp_title() for better SEO */
add_filter( 'wp_title', 'blankpress_wp_title', 10, 2 );

if(!function_exists('blankpress_wp_title')){
	function blankpress_wp_title( $title, $sep) {

		global $paged, $page;
		
		if ( is_feed() ) return $title;
		// Add the site name.
		$name = get_bloginfo( 'name' );
		
		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		
		// find the type of index page this is
		if ( $site_description && ( is_home() || is_front_page() ) ) $title = "$title $name $sep $site_description";
		elseif( is_category() ) $title = "$title Category $sep $name";
		elseif( is_tag() ) $title = "$title Tag $sep $name";
		elseif( is_author() ) $title = "$title  Author $sep $name";
		elseif( is_year() || is_month() || is_day() ) $title = "$title Archives $sep $name";
		else $title = "$title $name $sep $site_description";	
		
		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $name $sep " . sprintf( __( 'Page %s', TEXT_DOMAIN ), max( $paged, $page ) );
		
		return $title;
		 
	}
}

/* modify the excerpt length (default 55) */
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

if(!function_exists('custom_excerpt_length')){
	function custom_excerpt_length( $length ) {
		return  80;
	}
}

/*  replace "[...]" with an ellipsis  */
add_filter('excerpt_more', 'blankpress_auto_excerpt_more');

if(!function_exists('blankpress_auto_excerpt_more')){
	function blankpress_auto_excerpt_more($more) {
		return '<span class="ellipsis">&hellip;</span>';
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

/* fix the taxonomy and tags listing issue on CPT post screen */
add_filter( 'wp_terms_checklist_args', 'checklist_args' );

if(!function_exists('checklist_args')){
	function checklist_args( $args ) {
		//add_action( 'admin_footer', 'script' );
		$args['checked_ontop'] = false;
		return $args;
	}
}

/* Displays navigation to next/previous pages when applicable */
if ( ! function_exists( 'content_nav' ) ) {
	function content_nav( $html_id ) {
		global $wp_query;
		$html_id = esc_attr( $html_id );
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Post navigation', TEXT_DOMAIN ); ?></h3>
				<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', TEXT_DOMAIN ) ); ?></div>
				<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', TEXT_DOMAIN ) ); ?></div>
			</nav><!-- #<?php echo $html_id; ?> .navigation -->
		<?php endif;
	}
}

/* remove Admin bar */
//add_filter('show_admin_bar', 'remove_admin_bar'); 

// remove Admin bar
if ( ! function_exists( 'remove_admin_bar' ) ) {
	function remove_admin_bar(){
		return false;
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

/* add custom Gravatar in Settings > Discussion */
add_filter('avatar_defaults', 'custom_gravatar');

if ( ! function_exists( 'custom_gravatar' ) ) {
	function custom_gravatar($avatar_defaults){
		$custom_avatar = get_template_directory_uri() . '/img/gravatar.jpg';
		$avatar_defaults[$custom_avatar] = "BlankPress Gravatar";
		return $avatar_defaults;
	}
}

/* modify the default search */
add_filter('pre_get_posts','search_filter');

if(!function_exists('search_filter')){
	function search_filter($query) {
		if (($query->is_search or $query->is_feed) && get_query_var( 'post_type' ) ) {
			$query->set( 'post_type', array( get_query_var( 'post_type' ) ) );
		}
		return $query;
	}
}

/* enqueue gravity form scripts to the footer */
//add_filter("gform_init_scripts_footer", "gform_init_scripts_footer_cb");

if(!function_exists('gform_init_scripts_footer_cb')){
	function gform_init_scripts_footer_cb() {
		return true;
	}
}

/* enable excerpt on pages */
add_action( 'init', 'add_excerpts_to_pages' );
function add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}

// inclue shortcodes and custom post types
include_once('shortcodes.php');
include_once('functions-misc.php');


/*
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 *
 * @see    http://wordpress.stackexchange.com/q/14037/
 * @author toscho, http://toscho.de
 */
/* class Thumbnail_Walker extends Walker_Nav_Menu
{
/ **
 * Start the element output.
 *
 * @param  string $output Passed by reference. Used to append additional content.
 * @param  object $item   Menu item data object.
 * @param  int $depth     Depth of menu item. May be used for padding.
 * @param  array $args    Additional strings.
 * @return void
 * /
 function start_el(&$output, $item, $depth, $args) {
    $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

    $class_names = join(
        ' '
    ,   apply_filters(
            'nav_menu_css_class'
        ,   array_filter( $classes ), $item
        )
    );

    ! empty ( $class_names )
        and $class_names = ' class="'. esc_attr( $class_names ) . '"';

    $output .= "<li id='menu-item-$item->ID' $class_names>";

    $attributes  = '';

    ! empty( $item->attr_title )
        and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
    ! empty( $item->target )
        and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
    ! empty( $item->xfn )
        and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
    ! empty( $item->url )
        and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

    // insert thumbnail
    // you may change this
    $thumbnail = '';
    if( has_post_thumbnail( $item->object_id ) ) {
        $thumbnail = get_the_post_thumbnail( $item->object_id );
    }

    $title = apply_filters( 'the_title', $item->title, $item->ID );

    $item_output = $args->before
        . "<a $attributes>"
        . $args->link_before
        . $title
        . '</a> '
        . $args->link_after
        . $thumbnail
        . $args->after;

    // Since $output is called by reference we don't need to return anything.
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
   }
} */
