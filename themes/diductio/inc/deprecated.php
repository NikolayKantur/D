<?php
// Deprecated funcitons

function array_complite($post_id)
{
    global $view_path, $wpdb;
    
    $user_id = get_current_user_id();
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $sql .= "AND `post_id` = '{$post_id}'";
    $progress = $wpdb->get_results($sql);
    if (!empty($progress)) {
        if ($progress[0]->checked_lessons != 0) {
            $checked_lessons = count(explode(',', $progress[0]->checked_lessons));
        } else {
            $checked_lessons = 0;
        }
        $lessons_count = $progress[0]->lessons_count;
        
        if ($checked_lessons == $lessons_count) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function draw_user_progress($id)
{
    global $current_user, $wpdb;
    
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = {$id}";
    $progress = $wpdb->get_results($sql);
    $user_id = $id;
    foreach ($progress as $key => $value) {
        $post_id = $value->post_id;
        $html = diductio_add_progress($post_id, $user_id);
    }
}

function get_user_work_times($uid = 0)
{
    global $current_user, $wpdb;
    if ($uid === 0) {
        $uid = $current_user->ID;
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id`=$uid";
    $progress = $wpdb->get_results($sql);
    $wts = array(
        'all' => 0,
        'complete' => 0,
        'nocomplete' => 0,
    );
    
    foreach ($progress as $k => $v) {
        $wt = (int)get_post_meta($v->post_id, 'work_time', true);
        $wts['all'] += $wt;
        if ($wt != 0 and $v->checked_lessons != '0') {
            $cof = count(explode(',', $v->checked_lessons)) / $v->lessons_count;
            $wts['complete'] += floor($wt * $cof);
        }
    }
    $wts['nocomplete'] = $wts['all'] - $wts['complete'];
    
    return $wts;
}

function get_first_unchecked_lesson($post_id)
{
    global $wpdb;
    
    $user_id = get_current_user_id();
    if (!$user_id) {
        return false;
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $sql .= "AND `post_id` = '{$post_id}'";
    $progress = $wpdb->get_row($sql);
    if ($progress) {
        
        $all_lessons = range(1, $progress->lessons_count);
        $lessons_checked = explode(',', $progress->checked_lessons);
        
        if ($all_lessons && count($all_lessons) > 1) {
            $unchecked_array = array_diff($all_lessons, $lessons_checked);
            if (!empty($unchecked_array)) {
                $first_unchecked = min($unchecked_array);
                if ($first_unchecked) {
                    return "#lesson-" . $first_unchecked;
                }
            }
            
        }
    }
}