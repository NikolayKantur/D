<?php
    /*
    * Template Name: Шаблон "Все записи"
    * Description: Шаблон показывает все записи для авторизированого пользователя
    */
    $wp_query = new WP_Query(array('posts_per_page'=>10));
    get_header(); 
?>

	<div id="primary" class="content-area"> 	
            
                <?php do_action('index-head'); ?>              
  
            
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

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				 
                ?>
                    
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry-content <?=$removing_space_class;?>">
							
							<?php include(get_template_directory().'/view/article_header.php');?>
							
						</div>
						
					</article>
					
					<?php
                                 

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( '->', 'diductio' ),
				'next_text'          => __( '<-', 'diductio' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>',
			) );

                        // If no content, include the "No posts found" template.
                        else :
                                get_template_part( 'content', 'none' );

                        endif;
                        
                    ?>

		</main><!-- .site-main -->
                
	</div><!-- .content-area -->
<?php get_footer(); ?>