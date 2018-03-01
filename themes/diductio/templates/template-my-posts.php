<?php
/*
 * Template Name: Мои посты (публикации автора)
 * Данный шаблон выводит все авторские посты пользователя
*/
global $wp_query;

$username = get_query_var('username');
$author = $username ? get_user_by('slug', $username) : wp_get_current_user();

if($author && $author->ID) {
    $current_page = get_query_var('paged', 1);

    $Posts = new Wp_Query(array(
        "author" => $author->ID,
        'paged' => $current_page
    ));
}

get_header(); ?>
    <div id="primary" class="content-area">
        <?php do_action('author-page-header'); ?>
        <main id="main" class="site-main homepage-main" role="main">
            <?php if ( isset($Posts) && $author->ID ) : ?>
        
                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <header>
                        <h1 class="page-title screen-reader-text">
                            <?php single_post_title(); ?>
                        </h1>
                    </header>
                <?php endif; ?>
        
            <div id="post-entries">
                <?php
                // Start the loop.
                while ( $Posts->have_posts() ) : $Posts->the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <div class="entry-content <?=$removing_space_class;?>">
                            
                            <?php include(get_template_directory().'/view/article_header.php');?>
                            
                        </div>
                        
                    </article>
                    
                    <?php
            
                    // End the loop.
                endwhile;?>
            </div>
                <?php $postPerPage = get_option('posts_per_page');
            
            // echo do_shortcode('[ajax_load_more post_status="any" author="'.$author->ID.'" offset="'.$postPerPage.'"  button_label="Загрузить еще" button_loading_label="Загружаем..."]');

            echo do_shortcode('[ajax_load_more_users mode="posts" author="'.$author->ID.'" post_status="publish" per_page="' . $postPerPage . '" container="#post-entries"]');
            
            // If no content, include the "No posts found" template.
            else :
                get_template_part( 'templates/content/content', 'none' );
    
            endif;
            ?>
        </main>
    </div>
<?php get_footer(); ?>

