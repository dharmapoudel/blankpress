<?php
/*-------------------------------------------------
	BlankPress - Shortcodes
 --------------------------------------------------*/ 
 
//all shortcode related fucntion here

// check the current post for the existence of a short code  
if(!function_exists('has_shortcode')){
	function has_shortcode($shortcode = '') {  
		$post_to_check = get_post(get_the_ID());  
		// false because we have to search through the post content first  
		$found = false;  
		// if no short code was provided, return false  
		if (!$shortcode) {  
			return $found;  
		}  
		// check the post content for the short code  
		if ( stripos($post_to_check->post_content, '[' . $shortcode) !== false ) {  
			// we have found the short code  
			$found = true;  
		}  
		// return our final results  
		return $found;  
	}
}


/*  adding shortcodes to widget */
add_filter('comment_text', 'do_shortcode'); // allow shortcodes in comment
add_filter('widget_text', 'do_shortcode'); // allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('the_excerpt', 'do_shortcode'); // allow Shortcodes to be executed in Excerpt (Manual Excerpts only)



/* Add shortcodes to the wordpress editor (VISUAL AND TEXT) */

add_action('media_buttons','add_sc_select',11);
function add_sc_select(){
    global $shortcode_tags;
     /* enter names of shortcode to exclude bellow   */
    $exclude = array("wp_caption", "embed", 'caption', 'mailchimpsf_widget', 'mailchimpsf_form', 'column_demo', 'events_list');
    echo '&nbsp;<select id="sc_select" style="width:auto !important;"><option>Theme Shortcodes</option>';
    $shortcodes_list = "";
	foreach ($shortcode_tags as $key => $val){
		if(!in_array($key,$exclude)){
		$shortcodes_list .= '<option value="['.$key.'][/'.$key.']">'.$key.'</option>';
		}
    }
     echo $shortcodes_list;
     echo '</select>';
}

add_action('admin_footer', 'button_js');
function button_js() {
        echo '<script type="text/javascript">
        jQuery(document).ready(function(){
           jQuery("#sc_select").change(function() {
                          send_to_editor(jQuery("#sc_select :selected").val());
                          return false;
                });
        });
        </script>';
}

/* ----------- add shortcodes below  ----------------- */

/* Add shortcodes  first */
add_shortcode('blog_latest', 'blog_latest');
add_shortcode('image_slider', 'image_slider');

// [blog_latest]
function blog_latest($params = array(), $content = null) {
	extract(shortcode_atts(array(
		"numberofposts"	=> '2'
	), $params));
	ob_start();
	
	$args = array( 'post_type' => 'post', 'numberposts' =>$numberofposts, 'sortby' => 'date', 'sort' => 'ASC');
	$latest_posts= get_posts($args);
	if(!empty($latest_posts)): ?>
		<div class="latest-news clearfix">
		<h2> <?php echo $content; ?></h2>
		 <ul>
		 <?php foreach($latest_posts as $post): ?> 
		 <li>
			<h3><?php echo $post->post_title; ?></h3>
			<p><?php echo wp_trim_words($post->post_excerpt, 35, ''); ?></p>
			<a class="read_more" href="<?php echo get_permalink($post->ID); ?>">read more <span>&raquo; </span></a>
		 </li>
		 <?php endforeach; ?>
		 </ul>
		 <a class="view_more" href="<?php echo site_url(); ?>/blog">view all <span>&raquo;</span> </a>
		 </div>
	<?php endif; 
	
	$content = ob_get_contents();
	ob_end_clean();
	$content = ($content);
	return $content;
}
//

// slider
function image_slider($atts , $content=null){
	extract(shortcode_atts(array(
		'path'=>'',
		'caption' =>'',
		'link' =>'',
		'lightbox' =>true
	), $atts));
	
	$path= explode('|', $path);
	$caption= explode('|', $caption);
	$link= explode('|', $link);
	$count = count($path);
	if($count>0):
?>
	<aside class="slider">
        <h2 class="border_less title_first"><?php echo $content; ?></h2>
        <div class="flexslider widget_slider">
          <ul class="slides">
		  <?php for($i=0; $i<$count; $i++){ ?>
			 <li>
              <?php if($link[$i] !=''): ?>
			  <a <?php if($lightbox){ echo 'data-fancybox-group="slides"'; } ?>  class="slider_widget_link <?php if($lightbox){ echo ' fancybox'; } ?>" href="<?php echo $link[$i]; ?>"><?php endif; ?>
              <?php if($path[$i] !=''): ?><figure><img src="<?php echo $path[$i]; ?>" /></figure><?php endif; ?>
              <?php if($caption[$i] !=''): ?><figcaption><?php echo $caption[$i]; ?></figcaption><?php endif; ?>
			  <?php if($link[$i] !=''): ?></a><?php endif; ?>
            </li>
		 <?php } ?>
          </ul>
        </div>
     </aside>
<?php
	endif;
 }