<?php
/**
 * Template Name: Шаблон страницы "Авторы"
 * Description: Шаблон показывает всех авторов страницы
 */

$all_authors = array_map(function ($author) {
    return $author['post_author'];
}, Did_Posts::getAllAuthors());

$UserQuery = Did_Users::getUsersQuery(array(
    'include' => implode(',', $all_authors),
    'orderby' => 'post_count',
    'number' => 10,
));

get_header(); ?>

<div id="primary" class="content-area">
    <?php //do_action('all-peoples-header'); ?>
    <main id="main" class="site-main" role="main">
        <article id="users-page" class="page type-page status-publish hentry">
            <header class="entry-header tall">
                <h1 class="entry-title">Люди</h1>
                <div class="entry-content all-users">
                    
                    <?php
                    // User Loop
                    if ( ! empty( $UserQuery->results ) ) {
                        foreach ( $UserQuery->results as $user ) {
                            get_template_part( 'content', 'peoples' );
                        }
                    } else {
                        echo 'No users found.';
                    }
                    ?>
                </div>
            
            </header>
        </article>

        <?php echo do_shortcode('[ajax_load_more_users include="' . implode(',', $all_authors) . '" per_page="10"]'); ?>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer();