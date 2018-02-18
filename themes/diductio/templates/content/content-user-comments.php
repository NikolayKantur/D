<?php
    global $user_comments;

?>
<?php foreach ($user_comments as $comment):
    $comment_link = esc_url(get_comment_link($comment->comment_ID));
    $post_link = esc_url(get_permalink($comment->comment_post_ID));
    ?>

    <article class="post type-post status-publish format-quote hentry">
        <header class="entry-header">
            <h2 class="entry-title"><a href="<?=$post_link;?>" rel="bookmark"><?=get_the_title($comment->comment_post_ID);?></a></h2>
        </header>
        <div class="entry-content ">
            <p>
                <?=$comment->comment_content;?>
            </p>
        </div>
        <footer class="entry-footer">
            <?php if(!is_single()): ?>
                <div class="footer-statistic">
                    <?php
                    $st = (new Did_Statistic)->oldStatisticClass;
                    $post_statistic = $st->get_course_info($comment->comment_post_ID);
                    $post_statistic['total_progress'] = Did_Posts::getAllUsersProgress($post->ID);
                    $post_statistic['overdue_users'] = count(Did_Posts::getOverDueUsers($post->ID));
                    ?>
                    <?php if($post_statistic['in_progress'] > 0 ): ?>
                        <div class="stat-col">
                            <span class="label label-success label-soft">Проходят</span>
                            <span class="label label-success"><?=$post_statistic['in_progress'];?></span>
                            <?php if($post_statistic['overdue_users']): ?>
                                <span class="label label-danger"><?=$post_statistic['overdue_users'];?></span>
                            <?php endif; ?>
                            <?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] != 100): ?>
                                <span class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($post_statistic['done'] > 0 ): ?>
                        <div class="stat-col">
                            <span class="label label-success label-soft">Прошли</span>
                            <span class="label label-success"><?=$post_statistic['done'];?></span>
                            <?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] == 100): ?>
                                <span class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($post_statistic['les_count']): ?>
                        <div class="stat-col">
                            <span class="label label-success label-soft">Частей</span>
                            <span class="label label-success"><?=$post_statistic['les_count'];?></span>
                        </div>
                    <?php endif; ?>
                    <?php $approved = wp_count_comments( $post->ID )->approved;
                        if($approved > 0 ): ?>
                            <div class="stat-col">
                                <span class="label label-important-soft">Обсуждение</span>
                                <span class="label label-important"> <?=$approved; ?> </span>
                            </div>
                        <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php
                $post = get_post($comment->comment_post_ID);
                diductio_entry_meta();
            ?>
            <?php edit_post_link( __( 'Edit', 'diductio' ), '<span class="edit-link">', '</span>' ); ?>
        </footer><!-- .entry-footer -->
    </article>
<?php endforeach; ?>