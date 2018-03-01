<?php
/**
 * Template Name: Шаблон страницы "Группы"
 * Description: Шаблон показывает взаимные подписки
 */

$username = get_query_var('username');
$author = $username ? get_user_by('slug', $username) : wp_get_current_user();

$reciprocalSubscriptions = Did_User::getReciprocalSubscriptions($author->ID);
if (!empty($reciprocalSubscriptions)) {
    $UserQuery = Did_Users::getUsersQuery(array(
        'include' => implode(',', $reciprocalSubscriptions),
        'number' => Did_User::USERS_PER_PAGE,
    ));
}

get_header(); ?>

<div id="primary" class="content-area">
    <?php do_action('page-group-header'); ?>
    <main id="main" class="site-main" role="main">
        <article id="users-page" class="page type-page status-publish hentry">
            <header class="entry-header">
                <h1 class="entry-title">Люди</h1>
                <div class="entry-content all-users">
                    
                    <?php
                    // User Loop
                    if ( $UserQuery && ! empty( $UserQuery->results ) ) {
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

        <nav class="navigation pagination custom-page-wrapper" role="navigation">
            <div class="nav-links custom-pagination">
                <?php echo Did_Pagination::getPaginationForMutualSubscribes($UserQuery); ?>
            </div>
        </nav>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>

