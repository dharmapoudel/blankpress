<?php
/**
* BlankPress Theme Framework 1.3
* Date Updated: 2014-3-24
* Author: Dharma Poudel (@rogercomred)
*/

 
include_once('inc/blankpress-defaults.php');	// defines defaults and constants
include_once('admin/blankpress-admin.php');	// adds the theme options
include_once('inc/blankpress-setup.php');		// initializes the theme
include_once('inc/blankpress-enqueue.php');		// enqueues styles and scripts
include_once('inc/blankpress-register.php');	// registers widgets and menus
include_once('inc/blankpress-cleanup.php');		// cleans some WordPress mess
include_once('inc/blankpress-slider.php');		// adds BlankPress slider
include_once('inc/blankpress-utilities.php');	// includes some utility functions
include_once('inc/blankpress-shortcodes.php');	// includes theme shortcodes
include_once('inc/blankpress-custompost.php');	// adds BlankPress custom post type
include_once('inc/blankpress-metaboxes.php');	// includes theme metaboxes
include_once('inc/blankpress-sidebars.php');	// includes theme metaboxes


/* add blankpress defaults after init */
function add_blankpress_defaults() {
  global $blankpress_theme_defaults;
  if ( !get_option(THEME_SLUG.'_options') ){
    update_option(THEME_SLUG.'_options', $blankpress_theme_defaults);
  }
}
add_action('admin_init', 'add_blankpress_defaults');

function remove_blankpress_defaults() {
  delete_option(THEME_SLUG.'_options');
}
//add_action('switch_theme', 'remove_blankpress_defaults');