<?php 
/*-------------------------------------------------
	BlankPress - Utilities Functions
 --------------------------------------------------*/
 
 /* add meta tags on the header */
add_action( 'theme_meta', 'meta_cb' );

if (!function_exists('meta_cb')){
	function meta_cb(){
		echo "<meta charset='".get_bloginfo('charset')."'>\n";
		echo "<meta name='author' content='BlankPress'>\n";
		echo "<meta name='description' content='".get_bloginfo('description')."'>\n";
		echo "<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>\n";
		echo "<meta name='viewport' content='initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />\n";
		//echo "<meta name='viewport' content='width=1024' />\n"; // for testing purpose
	}
}
//load meta in header section by calling theme_meta()
function theme_meta(){
	do_action('theme_meta');
}

// add favicon to the head
add_action('wp_head', 'favicons_cb');

if (!function_exists('favicons_cb')){
	function favicons_cb() {
    $bp_defaults = get_option(THEME_SLUG.'_options');
		$favicon_url = $bp_defaults['favicon_url'];
		if(!empty($favicon_url)){
			echo '<link rel="shortcut icon" href="'.$favicon_url.'" type="image/x-icon" />'; 
		}
	}
}

// add web clip icons to the head
add_action('admin_head', 'favicons_backend_cb');

if (!function_exists('favicons_backend_cb')){
	function favicons_backend_cb() {
    $bp_defaults = get_option(THEME_SLUG.'_options');
		$favicon_url_backend = $bp_defaults['favicon_url_backend'];
		if(!empty($favicon_url_backend)){
			echo "<link rel='shortcut icon' href='$favicon_url_backend' type='image/x-icon' />\n"; 
		}
	}
}

// add web clip icons to the head
add_action('wp_head', 'webclip_icon_cb');

if (!function_exists('webclip_icon_cb')){
	function webclip_icon_cb() {
    $bp_defaults = get_option(THEME_SLUG.'_options');
		$webclip_iconurl = $bp_defaults['webclip_iconurl'];
		if(!empty($webclip_iconurl)){
				echo "<link rel='apple-touch-icon-precomposed'  href='$webclip_iconurl' />\n"; 
		}
	}
}

/* create custom hook to display site logo */
add_action('theme_logo', 'theme_logo_cb');

if (!function_exists('theme_logo_cb')){
	function theme_logo_cb() {
    $bp_defaults = get_option(THEME_SLUG.'_options');
		echo  "<img src='".$bp_defaults['logo_url']."' alt='".get_bloginfo('name')."' />";
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
    $bp_defaults = get_option(THEME_SLUG.'_options');
		$header_code = $bp_defaults['header_code'];
		if(!empty($header_code)){
			echo stripslashes($header_code);
		}
	}	
}

/* add custom code to the footer */
//add_action('wp_footer', 'blankpress_footer_code');

if (!function_exists('blankpress_footer_code')){
	function blankpress_footer_code(){
    $bp_defaults = get_option(THEME_SLUG.'_options');
		$footer_code = $bp_defaults['footer_code'];
		if(!empty($footer_code)){
			echo stripslashes($footer_code);
		}
	}	
}
 
 /* Adds "odd" class to all odd posts */
 add_filter ( 'post_class' , 'zebrastripe_post_class' );
 
global $current_class;
$current_class = 'odd';

function zebrastripe_post_class ( $classes ) { 
   if(!is_singular()) {
	   global $current_class;
	   $classes[] = $current_class;
	   $current_class = ($current_class == 'odd') ? 'even' : 'odd';
   }
   return $classes;
}

/* Adds links into the  WordPress admin bar */
add_action( 'admin_bar_menu', 'add_themeoptionlinkon_admin_bar', 1000 );

function add_themeoptionlinkon_admin_bar() {
    global $wp_admin_bar;

    if ( !is_super_admin() || !is_admin_bar_showing() )
        return;

    /* Add the main siteadmin menu item */
    $wp_admin_bar->add_menu( array( 								'id' => BP_DOMAIN.'-menu', 'title' => __(THEME_NAME, BP_DOMAIN), 'href' => admin_url('admin.php')."?page=".THEME_SLUG.'-options' ) );
    $wp_admin_bar->add_menu( array( 'parent' => BP_DOMAIN.'-menu', 'id' => 'bp-github', 'title' => __("Github", BP_DOMAIN), 'href' => 'https://github.com/dharmapoudel/blankpress' ) );
    $wp_admin_bar->add_menu( array( 'parent' => BP_DOMAIN.'-menu', 'id' => 'bp-twitter', 'title' => __("Twitter", BP_DOMAIN), 'href' => 'https://www.twitter.com/rogercomred' ) );
    $wp_admin_bar->add_menu( array( 'parent' => BP_DOMAIN.'-menu', 'id' => 'bp-facebook', 'title' => __("Facebook", BP_DOMAIN), 'href' => 'https://www.facebook.com/dharmapoudel' ) );

}

/* enqueue gravity form scripts to the footer */
add_filter("gform_init_scripts_footer", "gform_init_scripts_footer_cb");

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

/* add custom Gravatar in Settings > Discussion */
add_filter('avatar_defaults', 'custom_gravatar');

if ( ! function_exists( 'custom_gravatar' ) ) {
	function custom_gravatar($avatar_defaults){
		$custom_avatar = get_template_directory_uri() . '/img/gravatar.jpg';
		$avatar_defaults[$custom_avatar] = "BlankPress Gravatar";
		return $avatar_defaults;
	}
}

/*  replace "[...]" with an ellipsis  */
add_filter('excerpt_more', 'blankpress_auto_excerpt_more');

if(!function_exists('blankpress_auto_excerpt_more')){
	function blankpress_auto_excerpt_more($more) {
		return '<span class="ellipsis">&hellip;</span>';
	}
}

/* dynamically add id to body depending on page user is browsing */
if (!function_exists('dynamic_body_ids')){
  function dynamic_body_ids() {
    
    global $post;
    $id = '';
    if($post) $id = $post->post_name;
    if ( $post && ! empty( $post->post_parent ) ) $id .= '-'. $post->post_name;
    
    if ( is_front_page() ) { $id .= '-'."front-page"; }
    if ( is_home() ) { $id .= '-'."home-blog"; }
    if ( is_single() ) { $id .= '-'."single"; }
    if ( is_archive() ) { $id .= '-'."archive"; }
    if ( is_search() ) { $id .= '-'."search"; }
    if ( is_404() ) { $id .= '-'."error404"; }
    if ( is_multi_author() ) { $id .= '-'."group-blog"; }
    if ( get_header_image() ) { $id .= '-'."header-image";} 
    if ( is_attachment() ) { $id .= '-'."full-width"; }
    if ( is_singular() && ! is_front_page() ) { $id .= '-'."singular";}

    echo 'id='.$id;
  }
}
/* Extend the default WordPress body classes */
add_filter( 'body_class', 'dynamic_body_classes' );

function dynamic_body_classes( $classes ) {
  
  global $post;
  if($post) $classes[] = $post->post_name; 
  if ( $post && ! empty( $post->post_parent ) )  $classes[] = get_post($post->post_parent)->post_name; 
  
  if ( is_front_page() ) { $classes[] = 'front-page'; }
  if ( is_home() ) { $classes[] = 'homepage interior list-view'; }
  if ( is_single() ) { $classes[] = 'interior'; }
  if ( is_archive() ) { $classes[] = 'interior list-view'; }
  if ( is_search() ) { $classes[] = 'interior list-view'; }
  if ( is_404() ) { $classes[] = 'error404'; }
	if ( is_multi_author() ) { $classes[] = 'group-blog'; }
	if ( get_header_image() ) { $classes[] = 'header-image'; } 
	if ( is_attachment() ) { $classes[] = 'full-width'; }
	if ( is_singular() && ! is_front_page() ) {  $classes[] = 'singular'; }

	return $classes;
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
			$title = "$title $name $sep " . sprintf( __( 'Page %s', BP_DOMAIN ), max( $paged, $page ) );
		
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
				<h3 class="assistive-text"><?php _e( 'Post navigation', BP_DOMAIN ); ?></h3>
				<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', BP_DOMAIN ) ); ?></div>
				<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', BP_DOMAIN ) ); ?></div>
			</nav><!-- #<?php echo $html_id; ?> .navigation -->
		<?php endif;
	}
}


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

if ( ! function_exists( 'blankpress_post_meta' ) ) :
/**
 * Prints meta info for the current post.
 *
 * @since BlankPress 1.0
 */
function blankpress_post_meta() {
	global $post;

	$author_id		= $post->post_author;
	$author			= get_userdata( $author_id );
	$author_url		= get_author_posts_url( $author_id );
	$author_name	= $author->display_name;
	
	$comment_count = get_comments_number( $post->ID );
	$comment_number= $comment_count ? sprintf( _n( '1 comment', '%s comments', $comment_count, 'listingpress' ), $comment_count ) : __( 'No comments', 'listingpress' );
	
	$date = '<li class="post-meta-item"><span class="icon-text icon-calendar"><time class="entry-date" datetime="' . get_the_date( 'c' ) . '" pubdate>' . esc_html( get_the_date() ) . '</time></span></li>';
	$author = '<li class="post-meta-item"><span class="icon-text icon-agent"><a href="' . esc_url( $author_url ) . '" rel="author">' . esc_html( $author_name ) . '</a></span></li>';
	$comments = '<li class="post-meta-item"><span class="icon-text icon-comment"><a href="' . esc_url( get_permalink() ) . '#comments">' . esc_html( $comment_number ) . '</a></span></li>';
	
  $categories ='';
  if(!is_page()){
    $categories = '<li class="post-meta-item"><span class="icon-text icon-category">';
    $cats = get_the_category();
    $i = 0;
    foreach ( $cats as $cat ) {
      $out = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">';
      $out .= esc_html( $cat->name );
      $out .= '</a>';
      if ( $i > 0 )
        $out .= ', ';
      $i++;
      $categories .= $out;
    }
    $categories .= '</span></li>';
  }
	
	$out = '<ul class="post-meta group">';
	$out .= sprintf( __( '%1$s %2$s %3$s %4$s' ), $date, $author, $categories, $comments );
	$out .= '</ul>';
	
	echo $out;
}
endif; // blankpress_post_meta