<?php

class Did_Posts
{
    /**
     * @var Did_Statistic
     */
    private $staticClass;
    
    /**
     * Did_Posts constructor.
     */
    public function __construct()
    {
        $this->staticClass = new Did_Statistic();
    }

    public static function getCategoriesByPostsWithCurrentTag() {
        global $tag;

        // Get posta by Tag
        $tag_id = $tag->term_id;
        $args = array( 
            'tag__in' => $tag_id,
            'posts_per_page' => -1
        );
        $tag_posts = get_posts($args);

        $tag_categories = array();

        // Получаем категории по всем постам с этим тэгом
        foreach ($tag_posts as $tag_key => $tag_value) {
            $category_id = wp_get_post_categories($tag_value->ID);
            $category_info = get_category($category_id[0]);
            $category_link  = get_category_link($category_id[0]);

            $tmp_data['cat_id']   =  $category_info -> term_id;
            $tmp_data['cat_name'] =  $category_info -> name;
            $tmp_data['cat_link'] =  $category_link;
            $tag_categories[$category_info -> term_id] = $tmp_data;
        }

        return $tag_categories;
    }
    
    /**
     * Getting all authors
     * Получить всех авторов
     *
     * @return array|bool - Authors list or 'false' if there are not any authors
     */
    public static function getAllAuthors()
    {
        global $wpdb;
    
        $sql = "SELECT `ID`, `post_author` FROM `wp_posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' GROUP BY `post_author`";
        $result = $wpdb->get_results($sql, ARRAY_A);
        if ($result) {
            return $result;
        }
    
        return false;
    }
    
    /**
     * Get Post progress of all users by the Post
     * Получить прогресс всех пользователей по посту
     *
     * @param  int   $post_id - ID of the Post
     * @return float          - List of all users with their progress
     */
    public static function getAllUsersProgress($post_id)
    {
        $self = new self();
        
        $passing_users = $self->staticClass->oldStatisticClass->get_users_by_post($post_id);
        if ($passing_users) {
            $progress = 0;
            foreach ($passing_users as $user) {
                $progress += $user['progress'];
            }
            
            return round($progress / count($passing_users),2);
        }
        
        return 0;
    }
    
    /**
     * Ger list of the users, who overdue their posts
     * Получить список пользователей, которые просрочили выполнение задачи по срокам
     *
     * @param  int   $post_id - ID of the Post
     * @return array          - Users list
     */
    public static function getOverDueUsers($post_id)
    {
        $self = new self();
        $work_days = get_post_meta($post_id, 'work_time')[0];
        $users = $self->staticClass->oldStatisticClass->get_users_by_post($post_id);
        $overdue_users = [];
        foreach ($users as $user) {
            $started_at = $self->userStartedAt($user['user_id'], $post_id);
            $startedDate = new DateTime($started_at);
            $now = new DateTime();
            $worked = $now->diff($startedDate)->format('%a');
            if($worked > $work_days) {
                $overdue_users[] = $user['user_id'];
            }
        }
        
        return $overdue_users;
    }
    
    /**
     * When user has been started to learn the post
     * Когда пользователь начал проходит пост
     *
     * @param  int $user_id - ID of the Post
     * @param  int $post_id - ID of the User
     * @return bool         - Created at data or false if user did not started to learn it
     */
    public function userStartedAt($user_id, $post_id)
    {
        global $wpdb;
    
        $table = $this->staticClass->stat_table;
        $sql = "SELECT `created_at` FROM `{$table}` WHERE `post_id` = {$post_id} AND `user_id` = $user_id";
        $created_at = $wpdb->get_row($sql);
        if ($created_at) {
            return $created_at->created_at;
        }
    
        return false;
    }
    
    /**
     * Получить рейтинг пройденного поста (внутренний рейтинг)
     * Get rating of the passed post (inner rating)
     *
     * @param  int   $post_id - ID of the Post
     * @param  int   $user_id - ID of the User
     * @return array          - Rating data, including css class
     */
    public static function getPassedPostRating($post_id, $user_id)
    {
        global $wpdb;
        $self = new self();
        $table = $self->staticClass->stat_table;
        $sql = "SELECT * FROM `{$table}` WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}";
        $stat_info = $wpdb->get_row($sql, ARRAY_A);
        $work_time = get_post_meta($post_id, 'work_time')[0];
        $time_stamp = end(explode(',', $stat_info['checked_at']));
        
        $last_checked = new DateTime();
        $last_checked->setTimestamp($time_stamp);
        
        $created_at = new DateTime($stat_info['created_at']);
        
        $fact = $created_at->diff($last_checked)->format("%a");
        $data['class'] = 'label-danger';
        if ($fact) {
            $data['value'] = round($work_time / $fact, 1) * 100;
        } else {
            $data['value'] = round($work_time / 1, 1) * 100;
        }
        
        if ($data['value'] > 99) {
            $data['class'] = 'label-success';
        }
        
        return $data;
    }
    
    /**
     * Checking does provided post is in the cabinet of the provided user
     * Метод проверяет наличие поста в личном кабинете пользователя
     *
     * @param  int $user_id
     * @param  int $post_id
     * @return bool
     */
    public static function isPostInMyCabinet($user_id, $post_id)
    {
        global $wpdb;
        
        $table = Diductio::getInstance()->settings['stat_table'];
        $sql = "SELECT `id` FROM `{$table}` WHERE `user_id` = {$user_id} AND `post_id` = {$post_id} ";
        $result = $wpdb->get_results($sql, ARRAY_A);
        
        return !empty($result);
    }

    public static function getPostsFormatMap() {
        return array(
            'aside' => ['Знание', 'success'],
            'image' => ['Тест', 'important'],
            'quote' => ['Проект', 'important'],
            'video' => ['Видео', 'success'],
            'gallery' => ['Задача', 'important'],
            'chat' => ['Голосование', 'important'],
        );
    }
    
}