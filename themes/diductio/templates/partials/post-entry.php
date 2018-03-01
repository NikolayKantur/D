<?php
/**
 * The template used for displaying one post \ page entry
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php include(get_template_directory() . '/view/article_header.php'); ?> 
    </div>
</article>