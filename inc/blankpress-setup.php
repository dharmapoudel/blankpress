<?php
/*-------------------------------------------------
	BlankPress - Theme Setup
 --------------------------------------------------*/

// after theme set up : do some initialization
add_action('after_setup_theme', 'blankpress_setup');

if (!function_exists('blankpress_setup')){
	function blankpress_setup() {

		// Global content width (set the value based on the theme design)
		global $content_width;
		if (!isset($content_width)) $content_width = 650;
		
		//theme textdomain for translation
		load_theme_textdomain( BP_DOMAIN, get_template_directory() . '/languages');
		
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
    
    	/* set custom image size for featured images */
		//set_post_thumbnail_size( 604, 270, true );	
		// Custom image sizes
		add_image_size('thumbnail-bw', 600, 0, false); // generate black and white thumbnail
		add_image_size( $name = 'image21', $width = 600, $height = 300, $crop = true ); // 2:1
		add_image_size( $name = 'image43', $width = 320, $height = 240, $crop = true ); // 4:3
		add_image_size( $name = 'image169', $width = 320, $height = 180, $crop = true ); // 16:9
		
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
		
		/* Enable support for Post Formats */
		add_theme_support( 'post-formats', array(
		  'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		) );

		/* Enable search form, comment form, and comments to output valid HTML5 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );
	    
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
    
    /* disable media organization based on year and month */
    update_option('uploads_use_yearmonth_folders', 0);
    
    /* show only six posts per page */
    update_option('posts_per_page', 2);
    
    /* flush rewrite rules on theme activation */
    /* global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    flush_rewrite_rules(); */
    
	}
}