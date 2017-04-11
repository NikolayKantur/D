<?php
/*
 * Template Name: Прогресс
 * Данный шаблон показывает прогресс пользователя - мой прогресс.
 * Важная информация!!! Шаблон работает как и раньше с плагином wpf-favorite posts.
*/
get_header();

global  $dPost, $st;

$user_statistic = $st->get_user_info();
$author_info = wp_get_current_user();
$user_id = $author_info->ID;

// get categories information by user
$Did_Categories = new Did_Categories();
$category_statistic = $tag_statistic = array();
$category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value','desc')->max();
$tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value','desc')->max();

// get user progress
$favorite_post_ids = $st->get_knowledges($user_id);
$post_per_page     = wpfp_get_option("post_per_page");
$page              = intval(get_query_var('paged'));
$qry               = array(
    'post__in'       => $favorite_post_ids,
    'posts_per_page' => $post_per_page,
    'orderby'        => 'post__in',
    'paged'          => $page,
);
query_posts($qry);
?>

<!-- Page progress -->
<div id="primary" class="content-area">
    <?php do_action('progress-page-header'); ?>
    
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <?php view('cabinet', compact('category_statistic', 'author_info', 'tag_statistic', 'user_id', 'dPost', 'favorite_post_ids')); ?>
        <header>
    </main>
</div>
<!-- Page progress end -->

<?php get_footer(); ?>
