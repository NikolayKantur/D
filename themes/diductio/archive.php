<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
get_header();
$cat_id =get_query_var('cat') ;
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$queried_object = get_queried_object();
$tax = $queried_object->taxonomy;
$tax_name = $queried_object->name;
$tax_term = $queried_object->slug;
$tag = get_query_var('tag'); 
$cat = get_query_var('cat');
   $category = get_category ($cat);
?>
	<section id="primary" class="content-area">
		<?php// do_action('archive-header'); ?>
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="entry-title">', '</h1>' );
					// echo "<span class='label label-success label-soft'>Массивов &nbsp;</span>";
					// echo "<span class='label label-success label-number'>". get_category($cat_id)->category_count."</span>";
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
				
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content <?=$removing_space_class;?>">
						<?php include(get_template_directory().'/view/article_header.php'); ?> 
					</div>
				</article>
				
				<?php
			// End the loop.
			endwhile;
			
			$postPerPage = get_option('posts_per_page');
			
			echo do_shortcode('[ajax_load_more post_status="any" offset="'.$postPerPage.'" category="'.$category->slug.'" cache="true" cache_id="cache-'.$category->slug.'" tag="'.$tag.'" taxonomy="'. $tax .'" taxonomy_terms="'. $tax_term .'" taxonomy_operator="IN" button_label="Загрузить еще" button_loading_label="Загружаем..."]');
			
			/* Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'diductio' ),
				'next_text'          => __( 'Next page', 'diductio' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>',
			) );*/

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
