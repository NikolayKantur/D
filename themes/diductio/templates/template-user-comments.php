<?php
/*
 * Template Name: Активность (комментарии)
 * Данный шаблон выводит страницу активности пользователя (его комментарии)
*/
get_header();

$user_id = get_current_user_id();

if ($username = get_query_var('username', null)) {
    $user_obj = get_user_by('slug', $username);
    $user_id  = $user_obj->ID;
}

$per_page = Did_User::COMMENTS_PER_PAGE;

$user_comments = Did_User::getUserComments($user_id);

?>

<div id="primary" class="content-area">
    <?php do_action('page-user-comments-header'); ?>

    <main id="main" class="site-main" role="main">
        <header class="page-header">
            <h1 class="entry-title">
                <?php if($username) : ?>
                    Активность <?php echo $user_obj->display_name; ?>
                <?php else : ?>
                    Моя активность
                <?php endif; ?>
            </h1>
        </header>
                <?php
                    // Include the page content template.
                    get_template_part('templates/content/content', 'user-comments');
                ?>

        <?php if ($user_comments > $per_page): ?>
            <nav class="navigation pagination custom-page-wrapper" role="navigation">
                <div class="nav-links custom-pagination">
                    <?php echo Did_Pagination::getPaginationForUserComments($user_id); ?>
                </div>
            </nav>
        <?php endif; ?>
    </main>
</div>

<?php get_footer(); ?>
