<?php
/*-------------------------------------------------
	BlankPress - Default Footer Template
 --------------------------------------------------*/
?>

<footer class="footer container" role="contentinfo">
	<!--<nav role="navigation">
		<?php 
		if(has_nav_menu('secondary')){
			wp_nav_menu(array(
				'theme_location'  => 'secondary',
				'menu'            => '',
				'container'       => false,
				'menu_id' =>'',
				'menu_class' =>'bp-dropdown-menu footer-menu',
				'depth'           => 1
				//, 'walker' => new Thumbnail_Walker
			));
		}
		?>
	</nav> -->

	
	<ul class="socialmedia">
			<?php 
			$socialmedia_url = get_option('blankpress_socialmedia_url', true);
			
			if(!empty($socialmedia_url)){
				$socialmedia_name = get_option('blankpress_socialmedia_name', true);
				$socialmedia_iconurl = get_option('blankpress_socialmedia_iconurl', true);
				for($i=0; $i< count($socialmedia_url); $i++ ){ ?>
					<li>
					<a href="<?php echo $socialmedia_url[$i]; ?>" title="<?php echo $socialmedia_name[$i]; ?>" > 
						<img src="<?php echo $socialmedia_iconurl[$i]; ?>" alt="<?php echo $socialmedia_name[$i]; ?>" /> 
					</a>
					</li>
				<?php }
			}?>
	</ul>
</footer>

<!-- JavaScript added through functions.php to avoid conflicts  -->

<?php wp_footer(); ?>
</body>
</html>