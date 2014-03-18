<?php
/*-------------------------------------------------
	BlankPress - Default Header Template
 --------------------------------------------------*/
?>
<!doctype html>
<!--[if lt IE 7]>      <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 ie7"> <![endif]-->
<!--[if IE 8]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<?php theme_meta(); ?>
	
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php wp_head(); ?>
</head>

<body <?php dynamic_body_classes(); ?>>

<div class="container">
	<header class="header" role="banner">
		<div id="logo">
			<h1><a href="<?php bloginfo( 'url' ); ?>"><?php theme_logo(); ?></a></h1>
		</div>
		<nav class="main_nav clearfix" role="navigation">
			<?php if(has_nav_menu('primary')){
				wp_nav_menu(array(
					'theme_location'  => 'primary',
					'menu'            => '',
					'container'       => false,
					'menu_id' =>'bp-dropdown-menu',
					'menu_class' =>'bp-dropdown-menu',
					'depth'           => 0
					//, 'walker' => new Thumbnail_Walker
				));
			}else{ ?>
				<ul class="main-menu">
				<?php wp_list_pages( array(
					'depth'        => 0,
					'date_format'  => get_option('date_format'),
					'title_li'     => '',
					'echo'         => 1,
					'sort_column'  => 'menu_order, post_title',
				)); ?>
				</ul>
			<?php } ?>
		</nav>
	</header>
</div>