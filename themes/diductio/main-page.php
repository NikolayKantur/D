<?php
get_header(); ?>

	<div id="primary" class="content-area"> 	
            
                <?php //do_action('index-head'); ?>   
                    
		<main id="main" class="site-main homepage-main" role="main">
		
                    <?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>
                        
			<?php
			// Start the loop.
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
			
                        // If no content, include the "No posts found" template.
                        else :
                                get_template_part( 'templates/content/content', 'none' );

                        endif;
                        
                    ?>

		</main><!-- .site-main -->
                
	</div><!-- .content-area -->
<?php get_footer(); ?>