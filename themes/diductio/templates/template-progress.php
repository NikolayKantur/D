<?php
/*
 * Template Name: Прогресс
 * Данный шаблон показывает прогресс пользователя - мой прогресс.
 * Важная информация!!! Шаблон работает как и раньше с плагином wpf-favorite posts.
*/
get_header();

global $st;

$author_info = wp_get_current_user();
$user_id = $author_info->ID;
$favorite_post_ids = $st->get_knowledges($user_id);

if($favorite_post_ids) {
    $post_per_page = wpfp_get_option("post_per_page");
    $page = intval(get_query_var('paged'));

    $qry = array(
        'post__in' => $favorite_post_ids,
        'posts_per_page' => $post_per_page,
        'orderby' => 'post__in',
        'paged' => $page,
    );

    query_posts($qry);
}
?>

<!-- Page progress -->
<div id="primary" class="content-area">
    <?php do_action('progress-page-header'); ?>
    
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <?php diductio_view('cabinet', Did_Views::getParamsForView('cabinet')); ?>
        <header>
    </main>
</div>
<!-- Page progress end -->

<?php get_footer(); ?>
