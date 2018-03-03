<?php
get_header(); 

$username = get_query_var('username');
$user = $username ? get_user_by('slug', $username) : wp_get_current_user();

$post_status = 'publish';
if($user && $user->ID && $user->ID === get_current_user_id()) {
    $post_status = 'any';
}
?>

    <div id="primary" class="content-area">     
            
                <?php //do_action('index-head'); ?>   
                    
        <main id="main" class="site-main homepage-main" role="main">
        
                    <?php if ( have_posts() ) : ?>

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>
            <div id="post-entries">
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
            endwhile; ?>
            </div>

            <?php $postPerPage = get_option('posts_per_page');

            echo do_shortcode('[ajax_load_more_users mode="posts" taxonomy="'. $tax .'" taxonomy_terms="'. $tax_term .'" category="'.$category->slug.'" tag="'.$tag.'" post_status="' . $post_status . '" per_page="' . $postPerPage . '" container="#post-entries"]');
            
                        // If no content, include the "No posts found" template.
                        else :
                                get_template_part( 'templates/content/content', 'none' );

                        endif;
                        
                    ?>

        </main><!-- .site-main -->
                
    </div><!-- .content-area -->
<?php get_footer(); ?>