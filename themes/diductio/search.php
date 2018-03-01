<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

    <section id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'diductio' ), get_search_query() ); ?></h1>
            </header><!-- .page-header -->

            <div id="post-entries">
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post(); ?>

                    <?php
                    /*
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'templates/content/content', 'search' );

                // End the loop.
                endwhile; ?>
            </div>

            <?php $postPerPage = get_option('posts_per_page');
            // echo do_shortcode('[ajax_load_more post_status="publish" offset="'.$postPerPage.'" search="' . get_search_query() . '" button_label="Загрузить еще" button_loading_label="Загружаем..."]');

            echo do_shortcode('[ajax_load_more_users mode="posts" search="' . get_search_query() . '" post_status="publish" per_page="' . $postPerPage . '" container="#post-entries"]');

        // If no content, include the "No posts found" template.
        else :
            get_template_part( 'templates/content/content', 'none' );

        endif;
        ?>

        </main><!-- .site-main -->
    </section><!-- .content-area -->

<?php get_footer(); ?>
