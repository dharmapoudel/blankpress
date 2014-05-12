<?php
/*-------------------------------------------------
	BlankPress - Enqueue Styles and Scripts
 --------------------------------------------------*/

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
			false, 			// deps (array optional array()) - array of the handles that this script depends on
			'2.0.6', 		// ver(string optional false) - script version number. if no version specified or set to false wp adds its current version , if null no version number is added
			false			// in_footer (boolean optional false) - whether to place script in footer
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
    wp_register_style( 'Bootstrap', CSS_URL. 'bootstrap.css', array(), '1.0', 'all' );
		wp_register_style('Raleway', 'http://fonts.googleapis.com/css?family=Raleway', array(), '1.0', 'all');
		wp_register_style( 'flexslider', CSS_URL. 'flexslider.css', array(), '1.0', 'all');
		wp_register_style( 'fancybox', CSS_URL. 'jquery.fancybox.css', array(), '1.0', 'all' );
		
		// first load other stylesheets at once
		wp_enqueue_style( array('Bootstrap','Raleway','flexslider', 'fancybox'));
		
		// then load our main stylesheet
		wp_enqueue_style( 'blankpress-style', get_stylesheet_uri() );

		// finally load the Internet Explorer specific stylesheet.
		//global $wp_styles;
		//wp_enqueue_style( 'blankpress-ie', get_template_directory_uri() . '/css/ie.css', array( 'blankpress-style' ), '20130213' );
		//$wp_styles->add_data( 'blankpress-ie', 'conditional', 'lt IE 9' );
		//wp_style_add_data( 'blankpress-ie', 'conditional', 'lt IE 9' );
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
			wp_register_script( 'jquery', JS_URL.'jquery-1.8.2.min.js', false, '1.8.2', false);
			wp_enqueue_script( 'jquery' );
			
			// register JavaScript files
			wp_register_script( 'plugins',    JS_URL.'plugins.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'flexslider', JS_URL.'jquery.flexslider-min.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'fancybox',   JS_URL.'jquery.fancybox.js', array( 'jquery' ), '2.0.6', true );
			wp_register_script( 'jQueryNav',  JS_URL.'jQueryNav.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'main-js',    JS_URL.'scripts.js', array( 'jquery' ), '1.0', true );
			
			
			//load all scripts at once
			wp_enqueue_script( array('plugins', 'flexslider', 'fancybox', 'jQueryNav', 'main-js' ));
			
			//load fancybox only on gallery page
			//if ( is_page('gallery')) wp_enqueue_script( array('fancybox'));
			
			wp_localize_script('jquery','bp', array( 'adminUrl' => admin_url("admin-ajax.php") ));
		}
	}
}