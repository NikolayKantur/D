<?php
/**
 * Шаблон отвечает за вывод публичной статистики пользователя, типа: http://diductio.ru/people/{username}
 *
 * @link       https://codex.wordpress.org/Template_Hierarchy
 * @package    WordPress
 * @subpackage Twenty_Fifteen
 * @since      Twenty Fifteen 1.0
 */

get_header();

global $st;

$author = get_user_by('slug', get_query_var('author_name'));
$user_id = $author->ID;

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
} ?>

<section id="primary" class="content-area">
    <?php do_action('author-page-header'); ?>
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <?php diductio_view('cabinet', Did_Views::getParamsForView('cabinet2', ['author' => $author])); ?>
        </header><!-- .page-header -->
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>
