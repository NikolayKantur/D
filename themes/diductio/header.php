<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="top-bar">
	<header id="masthead" class="" role="banner">
		<div class="col-md-3 site-branding">
			<div class="logo-and-title">
			<?php
				twentyfifteen_the_custom_logo();

				if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<div class="undertitle">Создан для развития</div>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<div class="undertitle">Создан для развития</div>
				<?php endif;?>
			</div>

			<div class="secondary-button-holder">
				<button class="secondary-toggle"><?php _e( 'Menu and widgets', 'diductio' ); ?></button>
			</div>

			<div class="header-search">
				<?php dynamic_sidebar('sidebar-header'); ?>
			</div>
		</div><!-- .site-branding -->
		<div class="col-md-9 header-inner-wrapper">
			<div class="mobileNav">Разделы</div>
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'main', 'nav_walker' ) ); ?>
			
			<div class="header-search">
				<?php dynamic_sidebar('sidebar-header'); ?>
			</div>
		</div>
		</header><!-- .site-header -->

</div>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'diductio' ); ?></a>

	<div id="sidebar" class="sidebar">
		<?php get_sidebar(); ?>
	</div><!-- .sidebar -->

	<div id="content" class="site-content">
