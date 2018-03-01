<?php
/*
 * Template Name: Все люди
 * Данный шаблон страницы выводит всех пользователей на сайте
*/

$UserQuery = Did_Users::getUsersQuery(array(
    'orderby' => 'comments_count',
    'number' => 10,
));

get_header(); ?>

    <div id="primary" class="content-area">
        <?php //do_action('all-peoples-header'); ?>
        <main id="main" class="site-main" role="main">
            <article id="users-page" class="page type-page status-publish hentry">
                <header class="entry-header">
                    <h1 class="entry-title">Люди</h1>
                    <div class="entry-content all-users">

                            <?php
                                // User Loop
                                if ( ! empty( $UserQuery->results ) ) {
                                    foreach ( $UserQuery->results as $user ) {
                                        get_template_part( 'templates/content/content', 'peoples' );
                                    }
                                } else {
                                    echo 'No users found.';
                                }
                            ?>
                    </div>

                </header>
            </article>
            
        <?php echo do_shortcode('[ajax_load_more_users orderby="comments_count" per_page="10"]'); ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer();