<?php

class Did_User
{
    const COMMENTS_PER_PAGE = 60;
    const USERS_PER_PAGE = 10;
    /**
     * @var Did_Statistic
     */
    public $statistic;
    
    /**
     * Did_User constructor.
     */
    public function __construct()
    {
        $this->statistic = new Did_Statistic();
    }
    
    /**
     * Получить все посты пользователя
     * Get all posts by user
     *
     * @param string $user_id - ID пользователя | ID of the user
     * @return int $current_user_posts - Количество постов | Total posts count
     */
    public static function getAllMyPosts($user_id)
    {
        $args = array(
            'author'        =>  $user_id,
            'orderby'       =>  'post_date',
            'order'         =>  'ASC',
            'posts_per_page' => -1 // no limit
        );
        
        $current_user_posts = get_posts( $args );
        
        return count($current_user_posts);
    }
    
    public static function getAllMySubscribers($user_id)
    {
        $args = [
            'fields' => ['user_login', 'ID'],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'subscribe_to',
                    'value' => $user_id,
                    'compare' => 'LIKE',
                ],
            ],
        ];
        $users = new WP_User_Query($args);
        $result = array();
        
        foreach ($users->get_results() as $key => $user) {
            $result[$key]['ID'] = $user->ID;
            $result[$key]['user_login'] = $user->user_login;
        }
        
        return $result;
    }
    
    public static function getPassedPosts($user_id)
    {
        global $wpdb;
        
        $self = new self();
        $table = Diductio::gi()->settings['stat_table'];
        $sql = "SELECT * FROM `{$table}` WHERE `user_id` = {$user_id} ";
        $sql .="AND ((LENGTH(`checked_lessons`) - LENGTH(REPLACE(`checked_lessons`, ',', ''))+1) = `lessons_count`) ";
        $result = $wpdb->get_results($sql, ARRAY_A);
        
        return $result;
    }
    
    public static function getUserSubscription($user_id)
    {
        $subscriber_list = get_user_meta($user_id, 'subscribe_to')[0];
        if($subscriber_list) {
            foreach ($subscriber_list as $key => $user) {
                $user_exist = get_userdata($user);
                if(!$user_exist) {
                    unset($subscriber_list[$key]);
                }
            }
            
            return $subscriber_list;
        }
        return [];
    }
    
    /**
     * Getting reciprocal subscriptions
     * Получаем взаимные подписки пользователя
     *
     * @param  int   $user_id - User ID
     * @return array          - List of the reciprocal subscriptions
     */
    public static function getReciprocalSubscriptions($user_id)
    {
        $following = Did_User::getAllMySubscribers($user_id);
        $followers = Did_User::getUserSubscription($user_id);
        $result = array();
        foreach ($following as $user) {
            if(in_array($user['ID'], $followers)) {
                $result[] = $user['ID'];
            }
        }
        
        return $result;
    }

    public static function getUserComments($user_id) {
        $page      = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $limit     = self::COMMENTS_PER_PAGE;
        $offset    = ($page * $limit) - $limit;

        return get_comments(array(
            'offset'     => $offset,
            'author__in' => $user_id,
            'number'     => $limit,
        ));
    }

    public static function getTotalProgress() {
        $st = (new Did_Statistic)->oldStatisticClass;
        
        $current_user_id = get_current_user_id();
        $current_user_progress = false;
        $posts_users = $st->get_users_by_post($post->ID);
        // Find total progress
        $total_progress = 0;
        $num_users = 0;
        foreach ($posts_users as $user) {
            // Get current user progress
            if ($current_user_id && isset($user['user_id']) && $user['user_id'] === $current_user_id) {
                $current_user_progress = $user['progress'];
            }

            if (isset($user['progress']) && $user['progress'] > 0) {
                $total_progress += $user['progress'];
                ++$num_users;
            }
        }

        if ($total_progress > 0 && $num_users > 1) {
            $total_progress = round($total_progress / $num_users, 2);
        }

        return $total_progress;
    }

    public static function getEstimatedProgressForKnowledge($knowledge) {
        $st = (new Did_Statistic)->oldStatisticClass;

        // Считаем расчетный прогресс. (Примечание, обязательно в админ-панели должен быть прописан параметр work_time)
        $work_time = get_post_meta($knowledge->ID, 'work_time', true); // Заданное время для выполнения задания.
        $post_statistic = $st->get_course_info($knowledge->ID); // Информация о посте.
        $current_user_id = get_current_user_id(); // ID пользователя
        $started = $post_statistic['users_started'][$current_user_id]; // Начало выполнения задания.
    
        $now = date_create(); // Сегодняшняя дата.
        $start = date_create($started); // Создаем дату начала выполнения задания.
        $diff = date_diff($now, $start); // Количество пройденных дней с начала выполнения задания.        
    
        $diff_h_in_days = $diff->h > 0 ? $diff->h / 24 : 0; 

        $estimated_progress = round( ( ($diff->days + $diff_h_in_days) / $work_time ) * 100, 2);

        if ($estimated_progress >= 100) {
            $estimated_progress = 100; 
        }

        return $estimated_progress;
    }

    public static function getEstimatedProgressForPost($post_id) {
        $st = (new Did_Statistic)->oldStatisticClass;
        $post_statistic = $st->get_course_info($post_id);

        $work_time = (int)get_post_meta($post_id, 'work_time', true);

        $current_user_id = get_current_user_id();

        $started = $post_statistic['users_started'][$current_user_id];
        $now = date_create();
        $start = date_create($started);

        // date_add() modifies $end object
        $end = date_create($started);
        date_add($end, date_interval_create_from_date_string($work_time . ' days'));
        $diff = date_diff($now, $start);
        $countdown = date_diff($end, $now);
        
        $diff_h_in_days = $diff->h > 0 ? $diff->h / 24 : 0;
        $estimated_progress = 0;
        
        if ($work_time) {
            $estimated_progress = round(
                (
                    ($diff->days + $diff_h_in_days) / $work_time
                ) * 100,
                2
            );
        }

        if ($estimated_progress >= 100) {
            $estimated_progress = 100; 
        }

        return $estimated_progress;
    }
}