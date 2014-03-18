<?php
//define some constants if they are not defined already
if(! defined('THEME_NAME'))	define('THEME_NAME', 'BP'); // theme name
if(! defined('TEXT_DOMAIN')) define('TEXT_DOMAIN', 'blankpress'); // theme textdomain
if(! defined('TO_URL')) define('TO_URL', get_template_directory_uri().'/admin/'); // TO_URL - Theme Option Url

if (!class_exists('theme_option')):

class theme_option{
	private $theme_name;
	private $theme_textdomain;
	
	function __construct($themename, $theme_textdomain) {
		//initialize some basic variables
		$this->theme_name = $themename;
		$this->theme_textdomain = $theme_textdomain;
		
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array(&$this,'admin_enqueue_scripts_cb'));
		add_action('admin_print_styles', array(&$this, 'admin_enqueue_styles_cb'));
		add_action('wp_ajax_remove_socialmedia', array(&$this,'remove_socialmedia_cb'));
		add_action('wp_ajax_nopriv_remove_socialmedia', array(&$this,'remove_socialmedia_cb'));
	}
	function admin_menu () {
		$favicon_url = get_option($this->theme_textdomain.'_favicon_url');
		$icon_url = ($favicon_url !='') ? $favicon_url : TO_URL.'images/bp.png';
		// add menu page	
		add_menu_page( 
			__($this->theme_name." Options", $this->theme_textdomain), // Page Title
			__($this->theme_name."  Options", $this->theme_textdomain), // Menu Title
			'edit_theme_options', 						// Capability
			$this->theme_name.'-options', 				// Menu slug
			array($this, 'generate_html'),				// Callback function to display html
			$icon_url									// Icon url 
		);
		// add theme page
		/* add_theme_page(
			__($this->theme_name." Options", $this->theme_textdomain),
			__($this->theme_name."  Options", $this->theme_textdomain),
			'edit_theme_options',
			$this->theme_name.'-options',
			array($this, 'generate_html'),
			$icon_url
		); */
	}
	function admin_enqueue_scripts_cb($hook_suffix) {
		/* register */
		wp_register_script( 'jQueryTab', TO_URL . 'js/jQueryTab.js', array( 'jquery' ), '1.0' );
		wp_register_script( 'theme-options-js', TO_URL.'js/scripts.js', array( 'jquery' ), '1.0' );
		/* enqueue */
		wp_enqueue_script('media-upload');
		wp_enqueue_script( 'jQueryTab');
		wp_enqueue_script( 'theme-options-js');
		wp_localize_script('jquery','bp', array( 'adminUrl' => admin_url("admin-ajax.php") ));
	
	}
	function admin_enqueue_styles_cb($hook_suffix) {
		/* register */
		wp_register_style( 'theme-options-css', TO_URL. 'styles.css', array(), '1.4', 'screen' );
		/* enqueue */
		wp_enqueue_style('theme-options-css');
		wp_enqueue_style('thickbox', get_template_directory_uri(). 'wp-includes/js/thickbox/thickbox.css', false, false, 'screen'); 
	}
	function save_theme_options(){
			foreach ($_POST as $key => $value) {
				delete_option($this->theme_textdomain.'_'.$key, $value);
				add_option($this->theme_textdomain.'_'.$key, $value);
			}
	}
	function remove_socialmedia_cb(){
		$params = array();
		parse_str($_POST['data'], $params);
		foreach ($params as $key => $value) {
			delete_option($this->theme_textdomain.'_'.$key, $value);
			add_option($this->theme_textdomain.'_'.$key, $value);
		}
	}
	function generate_html(){ ?>
	
	<div class="wrap">  
        <?php screen_icon('themes'); ?> 
		<h2><?php echo __(ucfirst($this->theme_name)."Theme Options" ,$this->theme_textdomain); ?></h2>
		<?php if (count($_POST) > 0 && isset($_POST[$this->theme_name.'_theme_options'])) $this->save_theme_options(); ?>
		<nav class="">
		 <ul class="tabs">
			<li><a href="#">General</a></li>
			<li><a href="#">Social Media</a></li>
			<li><a href="#">Copyright</a></li>
			<li><a href="#">Contact</a></li>
			<li><a href="#">Advanced</a></li>
		 </ul>
		</nav>
        <form method="post" action="">
            
            <section class="tab_content">
				<h3><?php echo __("General Settings",$this->theme_textdomain); ?></h3>
				<div class="row">
					<div><label for="favicon_url"><?php echo __("Favicon",$this->theme_textdomain); ?></label></div>
					<div>
						<?php $favicon_url =  get_option($this->theme_textdomain.'_favicon_url'); ?>
						<img class="media_image <?php if($favicon_url != '') echo 'no_background'; ?> " src="<?php echo $favicon_url; ?>">
						<input type="text" name ="favicon_url" value="<?php echo $favicon_url; ?>" class="media_imageurl" />
						<span class="description"><?php echo __("click on image to upload favicon or add a link directly. ",$this->theme_textdomain); ?></span>
					</div>
				</div>
				<div class="row">
					<div><label for="logo_url"><?php echo __("Logo", $this->theme_textdomain); ?></label></div>
					<div>
						<?php $logo_url =  get_option($this->theme_textdomain.'_logo_url'); ?>
						<img class="media_image <?php if($logo_url != '') echo 'no_background'; ?> " src="<?php echo $logo_url; ?>">
						<input type="text" name ="logo_url" value="<?php echo $logo_url; ?>" class="media_imageurl" />
						<input type="text" name="logo_alt_text" value="<?php echo get_option($this->theme_textdomain.'_logo_alt_text'); ?>" />
						<span class="description"><?php echo __("click on image to upload logo or add a link directly, add an alternative text ",$this->theme_textdomain); ?></span>
					</div>
				</div>
				<div class="row">
					<div><label for="socialmedia_iconurl"><?php echo __("Web Clip Icons",$this->theme_textdomain); ?></label></div>
					<ul class="socialmedia">
					<?php 
					$webclip_iconsize = get_option($this->theme_textdomain.'_webclip_iconsize', true);
					if(!empty($webclip_iconsize)){
					$webclip_iconurl = get_option($this->theme_textdomain.'_webclip_iconurl', true);
						for($i=0; $i< count($webclip_iconsize); $i++ ){ ?>
							<li>
								<img class="media_image <?php if($webclip_iconurl[$i] != '') echo 'no_background'; ?> " src="<?php echo $webclip_iconurl[$i]; ?>">
								<input type="text" name ="webclip_iconurl[]" value="<?php echo $webclip_iconurl[$i]; ?>" class="media_imageurl media_url" />
								<input type="text" name="webclip_iconsize[]"  value="<?php echo $webclip_iconsize[$i]; ?>" class="media_text" />
								<a href="#" title="remove" class="option_remove"><span></span>remove</a>
							</li>
						<?php }
						} ?>
					</ul>
					<span class="description">
					<?php echo __("click on image to upload webclip icon or add url of the icon , add webclip icon size eg. 57x57",$this->theme_textdomain); ?></span>
					<a class="webclipicon_add" href="#"> add more</a>
					
				</div>
				<p class="submit clearfix">
				<button class=" progress-button">
					<span class="progress-bar"></span>
					<span class="progress-text">save changes</span>
					<span class="spiner" ></span>
				</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",$this->theme_textdomain); ?>" />
					<input type="hidden" name="<?php echo $this->theme_name; ?>_theme_options" value="save" style="display:none;" />
				</p>
            </section>
            

            
            <section class="tab_content">
				<h3><?php echo __("Social Links",$this->theme_textdomain); ?></h3>
				<div class="row">
					<div><label for="socialmedia_iconurl"><?php echo __("Media  Name",$this->theme_textdomain); ?></label></div>
					<ul class="socialmedia">
					<?php 
					$socialmedia_name = get_option($this->theme_textdomain.'_socialmedia_name', true);
					if(!empty($socialmedia_name)){
						$socialmedia_url = get_option($this->theme_textdomain.'_socialmedia_url', true);
						$socialmedia_iconurl = get_option($this->theme_textdomain.'_socialmedia_iconurl', true);
						for($i=0; $i< count($socialmedia_name); $i++ ){ ?>
							<li>
							
								<img class="media_image <?php if($socialmedia_iconurl[$i] != '') echo 'no_background'; ?>" src="<?php echo $socialmedia_iconurl[$i]; ?>">
								<input type="hidden" name ="socialmedia_iconurl[]" value="<?php echo $socialmedia_iconurl[$i]; ?>" class="media_imageurl" />
								<input type="text" name="socialmedia_name[]"  value="<?php echo $socialmedia_name[$i]; ?>" class="media_text" />
								<input type="text" name="socialmedia_url[]"  value="<?php echo $socialmedia_url[$i]; ?>" class="media_url" />
								<a href="#" class="option_remove"><span></span>remove</a>
							</li>
						<?php }
					}?>
					</ul>
					<span class="description"><?php echo __("click on image to upload, add media name and  media url",$this->theme_textdomain); ?></span>
					<a class="socialmedia_add" href="#"> add more</a>
					
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",$this->theme_textdomain); ?>" />
					<input type="hidden" name="<?php echo $this->theme_name; ?>_theme_options" value="save" style="display:none;" />
				</p>
            </section>
            
            
            <section class="tab_content">
				<h3><?php echo __("Copyright Info",$this->theme_textdomain); ?></h3>
				<div class="row">
					<div><label for="copyright"><?php echo __("Copyright Text",$this->theme_textdomain); ?></label></div>
					<div><input type="text" name="copyright" id="copyright" value="<?php echo get_option($this->theme_textdomain.'_copyright'); ?>" class="regular-text" /></div>
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",$this->theme_textdomain); ?>" />
					<input type="hidden" name="<?php echo $this->theme_name; ?>_theme_options" value="save" style="display:none;" />
				</p>
            </section>
            

            
            <section class="tab_content">
				<h3><?php echo __("Contact Information",$this->theme_textdomain); ?></h3>
				<div class="row">
					<div><label for="email"><?php echo __("Email ",$this->theme_textdomain); ?></label></div>
					<div><input type="text" name="email" id="email" value="<?php echo get_option($this->theme_textdomain.'_email'); ?>" class="regular-text" /></div>
				</div>
				<div class="row">
					<div><label for="phone"><?php echo __("Phone",$this->theme_textdomain); ?></label></div> 
					<div><input type="text" name="phone" id="phone" value="<?php echo get_option($this->theme_textdomain.'_phone'); ?>" class="regular-text" /> <span class="description"><?php echo __("Primary Phone Number",$this->theme_textdomain); ?></span></div>
				</div>
				<div class="row">
					<div><label for="phone_2"><?php echo __("Phone 2",$this->theme_textdomain); ?></label></div> 
					<div><input type="text" name="phone_2" id="phone_2" value="<?php echo get_option($this->theme_textdomain.'_phone_2'); ?>" class="regular-text" /> <span class="description"><?php echo __("Additional phone number",$this->theme_textdomain); ?></span></div>
				</div>
				<div class="row">
					<div><label for="address"><?php echo __("Address",$this->theme_textdomain); ?></label></div> 
					<div><input type="text" name="address" id="address" value="<?php echo get_option($this->theme_textdomain.'_address'); ?>" class="regular-text" /></div>
				</div>
				<p class="submit">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit clearfix" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",$this->theme_textdomain); ?>" />
					<input type="hidden" name="<?php echo $this->theme_name; ?>_theme_options" value="save" style="display:none;" />
				</p>
            </section>
			
			<section class="tab_content">
				<h3><?php echo __("Advanced Settings",$this->theme_textdomain); ?></h3>
				<div class="row">
					<div><label for="header_code"><?php echo __("Custom Header Code", $this->theme_textdomain); ?></label></div>
					<div><textarea type="text" name="header_code" id="header_code" class="regular-text" >
					<?php echo stripslashes(get_option($this->theme_textdomain.'_header_code')); ?>
					</textarea><span class="description"><?php echo __("Insert the code to place in header. Eg. custom styles", $this->theme_textdomain); ?></span></div>
				</div>
				<div class="row">
					<div><label for="footer_code"><?php echo __("Custom Footer Code", $this->theme_textdomain); ?></label></div>
					<div><textarea type="text" name="footer_code" id="footer_code" class="regular-text" >
					<?php echo stripslashes(get_option($this->theme_textdomain.'_footer_code')); ?>
					</textarea><span class="description"><?php echo __("Insert the code to place in footer. Eg. google analytics", $this->theme_textdomain); ?></span></div>
				</div>
				<p class="submit clearfix">
					<button class=" progress-button">
						<span class="progress-bar"></span>
						<span class="progress-text">save changes</span>
						<span class="spiner" ></span>
					</button>
					<input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __("Save Changes",$this->theme_textdomain); ?>" />
					<input type="hidden" name="<?php echo $this->theme_name; ?>_theme_options" value="save" style="display:none;" />
				</p>
            </section>
            
        </form>
    </div><!-- end wrap -->
	<?php }

}
new theme_option(THEME_NAME, TEXT_DOMAIN);

endif;