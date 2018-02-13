<?php
/*
 * Template Name: Сейчас проходят
 * Данный шаблон страницы выводит всех пользователей на сайте, которые проходят какие-либо массивы
 * либо выводит свободных пользователей
*/

global $st, $wp_roles;
if(!isset($st)) {
    global $st;
}

$args = array(
    'include' => array(),
    'exclude' => array(),
);

if (is_page('people-active')) {
    //Busy people
    $args['include'] = $st->get_all_users('active_users');
} else {
    //Free peoples
    $args['exclude'] = array_keys($st->busy_peoples);
}

// var_dump(array_keys($args['exclude']));
// exit;

$UserQuery = Did_Users::getUsersQuery(array_merge(array(
    'number' => 10,
), $args));

    get_header(); ?>
    
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <article id="users-page" class="page type-page status-publish hentry">
                <header class="entry-header">
                    <h1 class="entry-title">Люди</h1>
                    <div class="entry-content all-users">
                        <?php
                        // User Loop
                        if (!empty($UserQuery->results)) {
                            foreach ($UserQuery->results as $user) {
                                get_template_part('content', 'peoples');
                            }
                        } else {
                            echo 'No users found.';
                        }
                        ?>
                    </div>
                </header>
            </article>
            
            <?php echo do_shortcode('[ajax_load_more_users per_page="10" include="' . implode(',', $args['include']) . '" exclude="' . implode(',', $args['exclude']) . '"]'); ?>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
<?php get_footer(); ?>
