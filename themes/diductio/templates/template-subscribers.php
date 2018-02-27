<?php
/*
 * Template Name: Шаблон "Мои подписчики"
 * Данный шаблон страницы выводит всех пользователей на сайте
*/

$username = get_query_var('username');
$author = $username ? get_user_by('slug', $username) : wp_get_current_user();

$my_subscribers = array_map(function ($author) {
    return $author['ID'];
}, Did_User::getAllMySubscribers($author->ID));

if($my_subscribers) {
    $UserQuery = Did_Users::getUsersQuery(array(
        'include' => implode(',', $my_subscribers),
        'number' => Did_User::USERS_PER_PAGE,
    ));
}

get_header(); ?>

<div id="primary" class="content-area">
    <?php do_action('progress-subscribers-header'); ?>
    <main id="main" class="site-main" role="main">
        <article id="users-page" class="page type-page status-publish hentry">
            <header class="entry-header">
                <h1 class="entry-title">Подписчики</h1>
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

        <?php if ( ! empty( $UserQuery->results ) ) { ?>
        <nav class="navigation pagination custom-page-wrapper" role="navigation">
            <div class="nav-links custom-pagination">
                <?php echo Did_Pagination::getPaginationForSubscribers($UserQuery); ?>
            </div>
        </nav>
        <?php } ?>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
