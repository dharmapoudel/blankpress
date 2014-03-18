<?php 
/*-------------------------------------------------
	BlankPress - supplementary function page
 --------------------------------------------------*/
 


/* BlankPress Custom Post Type - uncomment below to add */
//add_action('init', 'create_custom_post_type'); 

// create BlankPress Custom Post type
if ( ! function_exists( 'create_custom_post_type' ) ) {
	function create_custom_post_type(){
		register_taxonomy_for_object_type('category', TEXT_DOMAIN); // register Taxonomies for Category
		register_taxonomy_for_object_type('post_tag', TEXT_DOMAIN);
		
		register_post_type('blankpress', // register Custom Post Type - blankpress
			array(
			'exclude_from_search' => false,
			'labels' => array(
				'name' => __('BlankPress Post', TEXT_DOMAIN),
				'singular_name' => __('BlankPress Custom Post', TEXT_DOMAIN),
				'add_new' => __('Add New', TEXT_DOMAIN),
				'add_new_item' => __('Add New BlankPress Custom Post', TEXT_DOMAIN),
				'edit' => __('Edit', 'html5blank'),
				'edit_item' => __('Edit BlankPress Custom Post', TEXT_DOMAIN),
				'new_item' => __('New BlankPress Custom Post', TEXT_DOMAIN),
				'view' => __('View BlankPress Custom Post', TEXT_DOMAIN),
				'view_item' => __('View BlankPress Custom Post', TEXT_DOMAIN),
				'search_items' => __('Search BlankPress Custom Post', TEXT_DOMAIN),
				'not_found' => __('No BlankPress Custom Posts found', TEXT_DOMAIN),
				'not_found_in_trash' => __('No BlankPress Custom Posts found in Trash', TEXT_DOMAIN)
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

/*  add meta box to the package screen -- uncomment below to enable */
//add_action('admin_init', 'add_gallery_meta_box');

if( ! function_exists('add_gallery_meta_box') ){
	function add_gallery_meta_box(){
		add_meta_box(
			"blankpress_gallery", 				// HTML id  attribute of the edit screen section
			"BlankPress Gallery", 				// title of the edit screen section
			"display_gallery", 				//callback function that prints html
			"blankpress", 				// post type on which to show edit screen
			"advanced", 					// context - part of page where to show the edit screen
			"default"						// priority where the boxes should show
		);	
	} 
}

function display_gallery() {?>
	<div class="row tab_content_gallery">
	<ul class="socialmedia">
		<?php 
		global $post;
		$pkg_media_url = get_post_meta($post->ID, TEXT_DOMAIN.'_pkg_media_url', true);
		if(!empty($pkg_media_url)){
		$pkg_media_title = get_post_meta($post->ID, TEXT_DOMAIN.'_pkg_media_title', true);
			for($i=0; $i< count($pkg_media_url); $i++ ){ ?>
				<li>
					<img class="media_image <?php if($pkg_media_url[$i] != '') echo 'no_background'; ?> " src="<?php echo $pkg_media_url[$i]; ?>">
					<input type="text" name ="pkg_media_url[]" value="<?php echo $pkg_media_url[$i]; ?>" class="media_imageurl media_url" />
					<input type="text" name="pkg_media_title[]"  value="<?php echo $pkg_media_title[$i]; ?>" class="media_text" />
					<a href="#" title="remove" class="pkg_option_remove"><span></span>remove</a>
				</li>
			<?php }
			} ?>
	</ul>
	<span class="description">
	<?php echo __("click on image to upload image or video or add url of the video or image , add title on the second box", TEXT_DOMAIN); ?></span>
	<a class="pkg_media_add" href="#"> add more</a>

	</div>
<?php }
 
/* Save custom field data when creating/updating posts  -- uncomment below to enable */
//add_action( 'save_post', 'save_package_custom_fields' );

function save_package_custom_fields(){
	global $post;
	if ($post) {
		foreach ($_POST as $key => $value) {
			delete_post_meta($post->ID, TEXT_DOMAIN.'_'.$key, $value);
			update_post_meta($post->ID, TEXT_DOMAIN.'_'.$key, $value);
		}
	}
}