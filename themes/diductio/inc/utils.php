<?php
// Helper functions starts here, other will removed into classes

/**
 * Include view of the diductio
 *
 * @param string $name - Name of the View
 * @param mixed  $data - Delegated data to the View
 * @return mixed - included page content
 */
function diductio_view($name, $data)
{
    $name = str_replace('.', DIRECTORY_SEPARATOR, $name);
    extract($data);
    return require get_template_directory() . "/view/{$name}.php";
}

function diductio_get_courses($is_complite = true)
{
    global $view_path, $wpdb;
    
    $user_id = get_current_user_id();
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $courses_array = array();
    
    
    $sql = "SELECT DISTINCT(`post_id`) FROM `$table_name`";
    $progress = $wpdb->get_results($sql);
    
    foreach ($progress as $course_key => $course_value) {
        $complite_count = 0;
        $in_progress_count = 0;
        $post_id = $course_value->post_id;
        $post_info = get_post($post_id);
        
        $sql = "SELECT  *  FROM `$table_name` WHERE ";
        $sql .= "`post_id` = '{$post_id}'";
        $post_progress_info = $wpdb->get_results($sql);
        
        foreach ($post_progress_info as $post_progress_key => $post_progress_value) {
            if ($post_progress_value->checked_lessons != 0) {
                $post_checked_lessons = count(explode(',', $post_progress_value->checked_lessons));
            } else {
                $post_checked_lessons = 0;
            }
            $post_lessons_count = $post_progress_value->lessons_count;
            if ($post_checked_lessons == $post_lessons_count) {
                $complite_count++;
            } else {
                $in_progress_count++;
            }
        }
        //print_r($post_info -> complite_count);
        if ($post_info != null) {
            $post_info->complite_count = $complite_count;
            $post_info->in_progress_count = $in_progress_count;
            $courses_array[$post_id] = $post_info;
        }
    }
    if ($is_complite) {
        usort($courses_array, function ($a, $b) {
            return $b->complite_count - $a->complite_count;
        });
    } else {
        usort($courses_array, function ($a, $b) {
            return $b->in_progress_count - $a->in_progress_count;
        });
    }
    foreach ($courses_array as $key => $value) {
        if ($is_complite && $value->complite_count == 0) {
            unset($courses_array[$key]);
        }
        if (!$is_complite && $value->in_progress_count == 0) {
            unset($courses_array[$key]);
        }
    }
    
    return $courses_array;
}

function diductio_excerp_comment($text, $size = 23)
{
    
    $comment_excerp_size = $size; //configuration;
    
    $excerpt = strip_shortcodes($text);
    $excerpt = strip_tags($excerpt);
    
    $str_lenght = strlen($excerpt);
    if ($str_lenght < $comment_excerp_size) {
        $the_str = $excerpt;
    } else {
        mb_internal_encoding("UTF-8");
        $the_str = mb_substr($excerpt, 0, $comment_excerp_size) . "…";
    }
    
    return $the_str;
}

function sort_desc($a, $b)
{
    if ($a['update_at'] < $b['update_at']) {
        return 1;
    }
}

function diductio_add_progress($post_id, $uid = false, $render = true)
{
    global $wpdb;
    
    if (!$uid) {
        $user_id = get_current_user_id();
    } else {
        $user_id = (int)$uid;
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $sql .= "AND `post_id` = '{$post_id}'";
    $progress = $wpdb->get_row($sql);
    if ($progress) {
        if ($progress->checked_lessons != "0") {
            $checked_count = count(explode(',', $progress->checked_lessons));
            $percent = round((100 * $checked_count) / $progress->lessons_count, 2);
        } else {
            $percent = 0;
        }
        
        $progressbar_class = "";
        if ($percent == 100) {
            $progressbar_class = "progress-bar-success";
        }
        
        $progress_html
            = "<div class='progress'>
                                <div class='progress-bar {$progressbar_class}' role='progressbar' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100' style='width:{$percent}%;'>
                                {$percent} %
                                </div>
                            </div>";
    } else {
        $progress_html
            = "<div class='progress'>
                                <div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width:0%;'>
                                0%
                                </div>
                            </div>";
    }
    if ($render) {
        echo $progress_html;
    } else {
        return $progress_html;
    }
}

// (33c) Модификация внешнего вида комментариев
function diductio_comments($comment, $args, $depth) { ?>
<li <?php comment_class(); ?> class="comment" id="li-comment-<?php comment_ID() ?>">
    <div class="comment-body" id="comment-<?php comment_ID(); ?>">
        <?php comment_text() ?>
        <div class="col-md-12 col-sm-12 col-xs-12 comment-meta-container">
            <div class="comment-meta" id="reply-link">
                <?php if (is_user_logged_in()): ?>
                    <?php comment_reply_link(array_merge($args,
                        array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                <?php endif; ?>
            </div>
            <div class="comment-meta comment-author">
                <?php
                // $user_name_str = substr(get_comment_author(),0, 20);
                $user_info = get_user_by('email', $comment->comment_author_email);
                $user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
                echo "<a href='{$user_link}'>";
                printf(__('<div class="inline"><b>%s</b></div>'), $user_info->data->display_name);
                printf(__('<div class="inline">%s</div>'), get_avatar($user_info->data->user_email));
                echo "</a>";
                ?>
            </div>
            <div class="comment-meta comment-date">
                <?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?>
            </div>
            <div class="comment-meta comment-edit">
                <?php edit_comment_link(__('Edit'), '&nbsp; ', ''); ?>
            </div>
        </div>
    </div>
    <?php
}   
// (33c) Модификация внешнего вида комментариев end