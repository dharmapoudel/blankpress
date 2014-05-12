<?php 
/*-------------------------------------------------
	BlankPress - Theme Defaults & Constants
 --------------------------------------------------*/
 
//define some constants if they are not defined already
if(! defined('THEME_NAME'))	define('THEME_NAME', 'BlankPress'); // theme name
if(! defined('THEME_SLUG'))	define('THEME_SLUG', 'BP'); // theme name
if(! defined('BP_DOMAIN'))  define('BP_DOMAIN', 'blankpress'); // theme textdomain
if(! defined('THEME_URL'))  define('THEME_URL', get_template_directory_uri()); // theme URL
if(! defined('CSS_URL'))    define('CSS_URL', THEME_URL. '/css/'); // css dir path
if(! defined('JS_URL'))     define('JS_URL', THEME_URL. '/js/'); // js dir path
if(! defined('IMG_URL'))    define('IMG_URL', THEME_URL. '/img/'); // img dir path
if(! defined('ADMIN_URL'))  define('ADMIN_URL', THEME_URL. '/admin/'); // admin dir path
if(! defined('INC_URL'))    define('INC_URL', THEME_URL. '/inc'); // inc dir path
 
global $blankpress_theme_defaults;
$blankpress_theme_defaults = array(
    'favicon_url' => '',
    'favicon_url_backend' => ADMIN_URL.'images/bp.png',
    'webclip_iconurl' => '',
    'logo_url' => get_template_directory_uri().'/img/logo.png',
    'socialmedia_name' => array(),
    'socialmedia_url' => array(),
    'socialmedia_iconurl' => array(),
    'copyright' => 'copyright &copy; '. date('Y'),
    'email' => get_option('admin_email'),
    'phone' => '',
    'address' => '',
    'header_code' => '',
    'footer_code' => ''
);