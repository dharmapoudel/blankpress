<?php
/*-------------------------------------------------
	BlankPress - Default Footer Template
 --------------------------------------------------*/
$bp_defaults = get_option(THEME_SLUG.'_options');
?>
<section class="footer_widgets group" >
  <div class="container">
  <?php
    if ( is_active_sidebar( 'sidebar-2' ) ) :
        dynamic_sidebar( 'sidebar-2' );
    endif;
  ?>
  </div>
</section>
<footer class="footer" role="contentinfo">
  <div class="container">
    <div class="row">
      <div class="copyright col-sm-12 col-md-3"><?php echo $bp_defaults['copyright']; ?></div>
      <nav role="navigation" class="col-sm-12 col-md-5 group">
        <?php 
        if(has_nav_menu('secondary')){
          wp_nav_menu(array(
            'theme_location'  => 'secondary',
            'menu'            => '',
            'container'       => false,
            'menu_id' =>'',
            'menu_class' =>'footer-menu',
            'depth'           => 1
          ));
        }
        ?>
      </nav>
      <ul class="socialmedia col-sm-12 col-md-4">
          <?php 
          $bp_defaults = get_option(THEME_SLUG.'_options');
          $socialmedia_url = isset($bp_defaults['socialmedia_url']) ?$bp_defaults['socialmedia_url'] :'' ;
          
          if(!empty($socialmedia_url)){
            $socialmedia_name = $bp_defaults['socialmedia_name'];
            $socialmedia_iconurl = $bp_defaults['socialmedia_iconurl'];
            for($i=0; $i< count($socialmedia_url); $i++ ){ ?>
              <li>
              <a href="<?php echo $socialmedia_url[$i]; ?>" title="<?php echo $socialmedia_name[$i]; ?>" > 
                <img src="<?php echo $socialmedia_iconurl[$i]; ?>" alt="<?php echo $socialmedia_name[$i]; ?>" /> 
              </a>
              </li>
            <?php }
          }?>
      </ul>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>