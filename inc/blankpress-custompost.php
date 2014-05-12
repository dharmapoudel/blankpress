<?php 
/*-------------------------------------------------
	BlankPress - Register Custom Post Type
 --------------------------------------------------*/


/* BlankPress Custom Post Type - uncomment below to add */
//add_action('init', 'create_custom_post_type'); 

// create BlankPress Custom Post type
if ( ! function_exists( 'create_custom_post_type' ) ) {
	function create_custom_post_type(){
		//register_taxonomy_for_object_type('category', BP_DOMAIN); // register Taxonomies for Category
		//register_taxonomy_for_object_type('post_tag', BP_DOMAIN);
		
		register_post_type('blankpress', // register Custom Post Type - blankpress
			array(
			'exclude_from_search' => false,
			'labels' => array(
				'name' => __('BlankPress Post', BP_DOMAIN),
				'singular_name' => __('BlankPress Custom Post', BP_DOMAIN),
				'add_new' => __('Add New', BP_DOMAIN),
				'add_new_item' => __('Add New BlankPress Custom Post', BP_DOMAIN),
				'edit' => __('Edit', 'html5blank'),
				'edit_item' => __('Edit BlankPress Custom Post', BP_DOMAIN),
				'new_item' => __('New BlankPress Custom Post', BP_DOMAIN),
				'view' => __('View BlankPress Custom Post', BP_DOMAIN),
				'view_item' => __('View BlankPress Custom Post', BP_DOMAIN),
				'search_items' => __('Search BlankPress Custom Post', BP_DOMAIN),
				'not_found' => __('No BlankPress Custom Posts found', BP_DOMAIN),
				'not_found_in_trash' => __('No BlankPress Custom Posts found in Trash', BP_DOMAIN)
			),
			'public' => true,
			'hierarchical' => true, // allow posts to behave like Hierarchy Pages
			'has_archive' => true,
			'supports' => array(	// support title, editor, excerpt and thumbnail
				'title',
				'editor',
				'excerpt',
				'thumbnail'
			), 
			'can_export' => true, // allow export in Tools > Export
			'taxonomies' => array( // add Category and Post Tags support
				'post_tag',
				'category'
			) 
		));
	}
}

// change the icon for custom post type - uncomment below to enable
//add_action('admin_head', 'blankpress_icon');

if( ! function_exists('blankpress_icon') ){
 function blankpress_icon(){ ?>
		<style type="text/css" media="screen">
		#adminmenu #menu-posts-blankpress div.wp-menu-image{
			background: url('<?php echo get_template_directory_uri(); ?>/images/bp.png') no-repeat 6px 6px;
		}
		</style>
		<?php 
	}
}


// now let's add custom categories
	register_taxonomy( 'custom_cat', 
		array('blankpress'),
		array('hierarchical' => true,     /* if true, it acts like categories */
			'labels' => array(
				'name' => __( 'Custom Categories', BP_DOMAIN ), /* name of the custom taxonomy */
				'singular_name' => __( 'Custom Category', BP_DOMAIN ), /* single taxonomy name */
				'search_items' =>  __( 'Search Custom Categories', BP_DOMAIN ), /* search title for taxomony */
				'all_items' => __( 'All Custom Categories', BP_DOMAIN ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Custom Category', BP_DOMAIN ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Custom Category:', BP_DOMAIN ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Custom Category', BP_DOMAIN ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Custom Category', BP_DOMAIN ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Custom Category', BP_DOMAIN ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Custom Category Name', BP_DOMAIN ) /* name title for taxonomy */
			),
			'show_admin_column' => true, 
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'custom-slug' ),
		)
	);
	
	// now let's add custom tags 
	register_taxonomy( 'custom_tag', 
		array('blankpress'), 
		array('hierarchical' => false,    /* if false, it acts like tags */
			'labels' => array(
				'name' => __( 'Custom Tags', BP_DOMAIN ), /* name of the custom taxonomy */
				'singular_name' => __( 'Custom Tag', BP_DOMAIN ), /* single taxonomy name */
				'search_items' =>  __( 'Search Custom Tags', BP_DOMAIN ), /* search title for taxomony */
				'all_items' => __( 'All Custom Tags', BP_DOMAIN ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Custom Tag', BP_DOMAIN ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Custom Tag:', BP_DOMAIN ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Custom Tag', BP_DOMAIN ),    /* edit custom taxonomy title */
				'update_item' => __( 'Update Custom Tag', BP_DOMAIN ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Custom Tag', BP_DOMAIN ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Custom Tag Name', BP_DOMAIN ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
		)
	);