<?php
if (!class_exists('bp_theme_option')):

class bp_theme_option{
	
	function __construct() {
		
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array(&$this,'admin_enqueue_scripts_cb'));
		add_action('admin_print_styles', array(&$this, 'admin_enqueue_styles_cb'));
		add_action('wp_ajax_remove_socialmedia', array(&$this,'remove_socialmedia_cb'));
		add_action('wp_ajax_nopriv_remove_socialmedia', array(&$this,'remove_socialmedia_cb'));
	}
	function admin_menu () {
		$favicon_url = get_option(BP_DOMAIN.'_favicon_url');
		$icon_url = ($favicon_url !='') ? $favicon_url : ADMIN_URL.'images/bp.png';
		// add menu page	
		add_menu_page( 
			__(THEME_NAME." Options", BP_DOMAIN), // Page Title
			__(THEME_SLUG."  Options", BP_DOMAIN), // Menu Title
			'edit_theme_options', 						// Capability
			THEME_SLUG.'-options', 				// Menu slug
			array($this, 'generate_html'),				// Callback function to display html
			$icon_url									// Icon url 
		);
		// add theme page
		/* add_theme_page(
			__(THEME_NAME." Options", BP_DOMAIN),
			__(THEME_SLUG."  Options", BP_DOMAIN),
			'edit_theme_options',
			THEME_SLUG.'-options',
			array($this, 'generate_html'),
			$icon_url
		); */
	}
	function admin_enqueue_scripts_cb($hook_suffix) {
		/* register */
		wp_register_script( 'jQueryTab', ADMIN_URL . 'js/jQueryTab.js', array( 'jquery' ), '1.0' );
		wp_register_script( 'theme-options-js', ADMIN_URL.'js/scripts.js', array( 'jquery' ), '1.0' );
		/* enqueue */
		wp_enqueue_script(array('media-upload', 'jQueryTab',  'theme-options-js'));
		/* wp_enqueue_script( 'jQueryTab');
		wp_enqueue_script( 'theme-options-js'); */
		wp_localize_script('jquery','bp', array( 'adminUrl' => admin_url("admin-ajax.php") ));
	
	}
	function admin_enqueue_styles_cb($hook_suffix) {
		/* register */
		wp_register_style( 'theme-options-css', ADMIN_URL. 'styles.css', array(), '1.4', 'screen' );
		wp_register_style( 'jQueryTab', ADMIN_URL. 'css/jQueryTab.css', array(), '1.4', 'screen' );
		wp_register_style( 'animation', ADMIN_URL. 'css/animation.css', array(), '1.4', 'screen' );
		/* enqueue */
		wp_enqueue_style(array('theme-options-css', 'jQueryTab', 'animation'));
		wp_enqueue_style('thickbox', get_template_directory_uri(). 'wp-includes/js/thickbox/thickbox.css', false, false, 'screen'); 
	}
	function save_theme_options($params){
    $options = array();
    foreach ($params as $key => $value) {
      $options[$key]= $value; 
    }
    delete_option(THEME_SLUG.'_options');
    add_option(THEME_SLUG.'_options', $options);
	}
  
	function remove_socialmedia_cb(){
		$params = array();
		parse_str($_POST['data'], $params);
    $this->save_theme_options($params);
	}
  
	function generate_html(){
    $options = get_option(THEME_SLUG.'_options');
    echo "<pre>"; print_r($options); echo "</pre>";
    if (count($_POST) > 0 && isset($_POST[THEME_NAME.'_theme_options'])) $this->save_theme_options($_POST); ?>
	
	<div class="wrap tabs-1">  
    <?php screen_icon('themes'); ?> 
		<h2><?php echo __(ucfirst(THEME_NAME)."Theme Options" ,BP_DOMAIN); ?></h2>
		
		<nav class="">
		 <ul class="tabs">
			<li><a href="#tab1">General</a></li>
			<li><a href="#tab2">Social Media</a></li>
			<li><a href="#tab3">Copyright</a></li>
			<li><a href="#tab4">Contact</a></li>
			<li><a href="#tab5">Advanced</a></li>
		 </ul>
		</nav>
    <form method="post" action="" class="tab_content_wrapper">
      <section id="tab1" class="tab_content">
				<div class="row">
					<div><label for="favicon_url"><?php echo __("Favicon Frontend",BP_DOMAIN); ?></label></div>
					<div>
						<img class="media_image <?php if($options['favicon_url'] != '') echo 'no_background'; ?> " src="<?php echo $options['favicon_url']; ?>">
						<input type="text" name ="favicon_url" value="<?php echo $options['favicon_url']; ?>" class="media_imageurl" />
						<span class="description"><?php echo __("click on image to upload favicon or add a link directly. ",BP_DOMAIN); ?></span>
					</div>
				</div>
        <div class="row">
					<div><label for="favicon_url_backend"><?php echo __("Favicon Backend",BP_DOMAIN); ?></label></div>
					<div>
						<?php $favicon_url_backend =  $options['favicon_url_backend']; ?>
						<img class="media_image <?php if($favicon_url_backend != '') echo 'no_background'; ?> " src="<?php echo $favicon_url_backend; ?>">
						<input type="text" name ="favicon_url_backend" value="<?php echo $favicon_url_backend; ?>" class="media_imageurl" />
						<span class="description"><?php echo __("click on image to upload favicon or add a link directly. ",BP_DOMAIN); ?></span>
					</div>
				</div>
        <div class="row">
					<div><label for="webclip_iconurl"><?php echo __("Web Clip Icon", BP_DOMAIN); ?></label></div>
					<div>
						<?php $webclip_iconurl =  $options['webclip_iconurl']; ?>
						<img class="media_image <?php if($webclip_iconurl != '') echo 'no_background'; ?> " src="<?php echo $webclip_iconurl; ?>">
						<input type="text" name ="webclip_iconurl" value="<?php echo $webclip_iconurl; ?>" class="media_imageurl" />
						<span class="description"><?php echo __("click on image to upload webclip icon or add url of the icon.", BP_DOMAIN); ?></span>
					</div>
				</div>
				<div class="row">
					<div><label for="logo_url"><?php echo __("Logo", BP_DOMAIN); ?></label></div>
					<div>
						<?php $logo_url = $options['logo_url']; ?>
						<img class="media_image <?php if($logo_url != '') echo 'no_background'; ?> " src="<?php echo $logo_url; ?>">
						<input type="text" name ="logo_url" value="<?php echo $logo_url; ?>" class="media_imageurl" />
						<span class="description"><?php echo __("click on image to upload logo or add a link directly.",BP_DOMAIN); ?></span>
					</div>
				</div>
        
				
				<p class="submit clearfix">
				<button class=" progress-button">
					<span class="progress-bar"></span>
					<span class="progress-text">save changes</span>
					<span class="spiner" ></span>
				</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",BP_DOMAIN); ?>" />
					<input type="hidden" name="<?php echo THEME_NAME; ?>_theme_options" value="save" />
				</p>
      </section>   
      <section id="tab2" class="tab_content">
				<div class="row">
					<ul class="socialmedia">
					<?php 
					$socialmedia_name = isset($options['socialmedia_name'])? $options['socialmedia_name']: '';
					if(!empty($socialmedia_name)){
						$socialmedia_url = $options['socialmedia_url'];
						$socialmedia_iconurl = $options['socialmedia_iconurl'];
						for($i=0; $i< count($socialmedia_name); $i++ ){ ?>
							<li>
							
								<img class="media_image <?php if($socialmedia_iconurl[$i] != '') echo 'no_background'; ?>" src="<?php echo $socialmedia_iconurl[$i]; ?>">
								<input type="hidden" name ="socialmedia_iconurl[]" value="<?php echo $socialmedia_iconurl[$i]; ?>" class="media_imageurl" />
								<input type="text" name="socialmedia_name[]"  value="<?php echo $socialmedia_name[$i]; ?>" class="media_text" />
								<input type="text" name="socialmedia_url[]"  value="<?php echo $socialmedia_url[$i]; ?>" class="media_url" />
								<a href="#" class="option_remove"><span>x</span></a>
							</li>
						<?php } 
            } ?>
					</ul>
          <span class="description <?php echo empty($socialmedia_name)?' hide': ''; ?>"><?php echo __("click on image to upload, add media name and  media url",BP_DOMAIN); ?></span>
					<a class="socialmedia_add" href="#"><span>+</span> <?php _e('add more'); ?></a>
					
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",BP_DOMAIN); ?>" />
					<input type="hidden" name="<?php echo THEME_NAME; ?>_theme_options" value="save" />
				</p>
      </section>
      <section id="tab3" class="tab_content">
				<div class="row">
					<div><label for="copyright"><?php echo __("Copyright Text",BP_DOMAIN); ?></label></div>
					<div><input type="text" name="copyright" id="copyright" value="<?php echo $options['copyright']; ?>" class="regular-text" /></div>
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",BP_DOMAIN); ?>" />
					<input type="hidden" name="<?php echo THEME_NAME; ?>_theme_options" value="save" />
				</p>
      </section>
      <section  id="tab4" class="tab_content">
				<div class="row">
					<div><label for="email"><?php echo __("Email ", BP_DOMAIN); ?></label></div>
					<div><input type="text" name="email" id="email" value="<?php echo $options['email']; ?>" /></div>
				</div>
				<div class="row">
					<div><label for="phone"><?php echo __("Phone", BP_DOMAIN); ?></label></div> 
					<div><input type="text" name="phone" id="phone" value="<?php echo $options['phone']; ?>" /> 
          <span class="description"><?php echo __("Primary Phone Number",BP_DOMAIN); ?></span></div>
				</div>
				<div class="row">
					<div><label for="phone_2"><?php echo __("Phone 2", BP_DOMAIN); ?></label></div> 
					<div><input type="text" name="phone_2" id="phone_2" value="<?php echo $options['phone_2']; ?>" /> 
          <span class="description"><?php echo __("Additional phone number",BP_DOMAIN); ?></span></div>
				</div>
				<div class="row">
					<div><label for="address"><?php echo __("Address",BP_DOMAIN); ?></label></div> 
					<div><input type="text" name="address" id="address" value="<?php echo $options['address']; ?>" /></div>
				</div>
				<p class="submit">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit clearfix" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",BP_DOMAIN); ?>" />
					<input type="hidden" name="<?php echo THEME_NAME; ?>_theme_options" value="save" />
				</p>
      </section>
			<section  id="tab5" class="tab_content">
				<div class="row">
					<div><label for="header_code"><?php echo __("Custom Header Code", BP_DOMAIN); ?></label></div>
					<div>
          <textarea type="text" name="header_code"><?php echo stripslashes($options['header_code']); ?></textarea>
          <span class="description"><?php echo __("Insert the code to place in header. Eg. custom styles", BP_DOMAIN); ?></span></div>
				</div>
				<div class="row">
					<div><label for="footer_code"><?php echo __("Custom Footer Code", BP_DOMAIN); ?></label></div>
					<div>
          <textarea type="text" name="footer_code"><?php echo stripslashes($options['footer_code']); ?></textarea>
          <span class="description"><?php echo __("Insert the code to place in footer. Eg. google analytics", BP_DOMAIN); ?></span></div>
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",BP_DOMAIN); ?>" />
					<input type="hidden" name="<?php echo THEME_NAME; ?>_theme_options" value="save" />
				</p>
      </section>
            
    </form>
  </div><!-- end wrap -->
	<?php }

}
new bp_theme_option();

endif;