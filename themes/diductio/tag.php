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
global $wp_query;
$post_count  = $wp_query->post_count;

if (is_tag()) {
	$tag = get_queried_object();
	$tag_id = $tag->term_id;
}

get_header();
?>

	<section id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<div class="stat-col">
				<span class="label label-success label-soft">Массивы</span>
				<span class="label label-success"><?=$post_count;?></span>
			</div>
			<?php
				if (function_exists('getSubsriberView')) {
					echo getSubsriberView('tag'); 
				}
			?>
		</div>
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			
			<article class='hentry'>
				<header class="entry-header">
					<?php
						the_archive_title( '<h1 class="entry-title">', '</h1>' );
						// echo "<span class='label label-success label-soft'>Массивов &nbsp;</span>";
						// echo "<span class='label label-success label-number'>". $wp_the_query->post_count."</span>";
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header>
				<footer class="entry-footer">
					<span class="screen-reader-text">Рубрики </span>
						<?php
						 if(isset($tag_id) && $tag_id)	{
		    				$tag_categories = Did_Post::getTagCategoriesBy($tag_id);

		    				foreach ($tag_categories as $key => $value) { ?>
		    					<span class="cat-links 2">
		    						<a href="<?php echo $value['cat_link'] ?>">
		    							<?php echo $value['cat_name'] ?>
		    						</a>
		    					</span>
		    				<?php }	
						 }
						 ?>
				</footer>
			</article>

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'templates/content/content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'diductio' ),
				'next_text'          => __( 'Next page', 'diductio' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'templates/content/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>

