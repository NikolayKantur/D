<?php
/**
 * Template Name: Шаблон страницы Массивы
 */

if(is_page('array-recently')) {
    $courses = diductio_get_courses();
} else { 
    $courses = diductio_get_courses(false);
}

$current_page = max(1, get_query_var('page'));

$courses_count = count($courses);
$courses = array_slice($courses, ($current_page -1 ) * 10, 10, true);

get_header(); ?>

<div id="primary" class="content-area">
    <?php do_action('knowledge-header'); ?>
    <main id="main" class="site-main" role="main">
    <?php
    // Start the loop.
    foreach($courses as $post) {
        if($post->ID) {
            setup_postdata($post);
            get_template_part('templates/content/content', 'array');
        }
    }
    ?>
    <?php if($courses_count > 10): ?>
        <nav class="navigation pagination custom-page-wrapper" role="navigation">
            <div class="nav-links custom-pagination">
                <?php echo Did_Pagination::getPaginationForArrays(); ?>
            </div>
        </nav>
    <?php endif; ?>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
