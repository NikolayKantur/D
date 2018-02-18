<?php
// (6) Функция вывода меток на странице Источники
function diductio_func_list_sources($atts)
{
    global $view_path;
    $tags = get_tags();
    if (file_exists($view_path . "istochniki_page.php")) {
        require_once($view_path . "istochniki_page.php");
    }
}

// (7) Шорткод вывода ленты меток
add_shortcode('istochniki', 'diductio_func_list_sources');

// (32) Шорткод страницы  "Моя зачетка"
add_shortcode('zachetka', 'diductio_moya_zachetka');
function diductio_moya_zachetka()
{
    global $view_path, $wpdb, $author_info;
    
    $author = get_user_by('slug', get_query_var('author_name'));
    if ($author_info) {
        $user_id = $author_info->ID;
    } else {
        $user_id = get_current_user_id();
    }
    
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $progress = $wpdb->get_results($sql);
    $learned_lessons = array();
    foreach ($progress as $lesson_key => $lesson_value) {
        $lessons_count = $lesson_value->lessons_count;
        if ($lesson_value->checked_lessons != 0) {
            $checked_lessons = explode(',', $lesson_value->checked_lessons);
            $checked_lessons_count = count($checked_lessons);
        } else {
            $checked_lessons_count = 0;
        }
        if ($checked_lessons_count == $lessons_count) {
            $post_info = get_post($lesson_value->post_id);
            $new_data['post_title'] = $post_info->post_title;
            $new_data['post_url'] = get_permalink($post_info->ID);
            $learned_lessons[$lesson_key] = $new_data;
        }
    }
    unset($new_data);
    if (file_exists($view_path . "moya_zachetka_page.php")) {
        $is_author_page = $author_info->data->user_login;

        require_once($view_path . "moya_zachetka_page.php");
    }
}

// (32) Шорткод страницы  "Моя зачетка"  end

// function get_my_comments()
// {
//     global $view_path;
    
//     $user = wp_get_current_user();
//     $args = array(
//         'author__in' => $user->id,
//     );
//     $user_comments = get_comments($args);
    
//     if (file_exists($view_path . "my_comments_page.php")) {
//         require_once($view_path . "my_comments_page.php");
//     }
// }
// add_shortcode( 'my_comments', 'get_my_comments' );