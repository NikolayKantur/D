<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
global $st; 

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-content <?=$removing_space_class;?>">
        
        <?php include(get_template_directory().'/view/article_header.php');?>

        <?php
            /* translators: %s: Name of current post */
            if ( is_single ) {
                the_content( sprintf(
                    the_title( '<span class="screen-reader-text">', '</span>', false )
                ) );
            };

            wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'diductio' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>%',
                'separator'   => '<span class="screen-reader-text">, </span>',
            ) );

        ?>

        <!-- (17) Добавление чекбокса на страницу с отсутствующим акордеоном -->
        <?php if ( is_single()): 

            $accordion_exist = false;
            if(class_exists('Accordion_Shortcodes')) {
                $accordion_exist = Accordion_Shortcodes::$accordion_exsit;
            }

        ?>
            <?php if(!$accordion_exist):
                $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
                $user_id = get_current_user_id();
                $sql  = "SELECT `checked_lessons` FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
                $sql .= "AND `post_id` = '{$post->ID}'";
                $progress = $wpdb->get_row($sql);
                $isMine = Did_Posts::isPostInMyCabinet($user_id, $post->ID);
                
                if($progress->checked_lessons) {
                    $checkbox_attr = "checked='checked' disabled='disabled'";
                }
            ?>
             <?php if(is_user_logged_in() && $isMine): ?>
                 <div class="col-md-1 col-xs-2" style="height: 0;">
                        <div style="height: 22px;" class="checkbox inline">
                            <input id="checkbox-<?=$post->ID;?>" type="checkbox" class="accordion-checkbox" data-accordion-count="1" data-post-id="<?=$post->ID;?>" <?=$checkbox_attr?> >
                            <label for="checkbox-<?=$post->ID;?>"></label>
                        </div>
                 </div>
                 <div class="col-md-3 col-xs-5 checkbox-label">
                        <label for="checkbox-<?=$post->ID;?>">Готово!</label>
                 </div>
            <?php endif;?>
        <?php endif;?>
    <?php endif;?>
    <!-- (17) Добавление чекбокса на страницу с отсутствующим акордеоном end-->

    </div><!-- .entry-content -->
    
</article><!-- #post-## -->