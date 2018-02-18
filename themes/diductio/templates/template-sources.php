<?php
/**
 * Template Name: Шаблон страницы "Источники"
 */
$progress = Did_Sources::getSources();

get_header(); ?>
<div id="primary" class="content-area">
	<?php do_action('istochniki-header'); ?>
	<main id="main" class="site-main" role="main">
	<?php
	
	foreach($progress as $tag_info) {
		$tag = get_tag($tag_info->term_id);
		get_template_part( 'templates/content/content', 'sources' );
	}

	?>

	<nav class="navigation pagination custom-page-wrapper" role="navigation">
		<div class="nav-links custom-pagination">
			<?php echo Did_Pagination::getPaginationForSources(); ?>
		</div>
	</nav>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
