<?php
class Did_Views
{
    private static function viewPeopleSingleRowParams($user) {
        global $st, $dUser, $dPost;

        $Did_Categories = new Did_Categories();

        // Получим данные пользователя
        $user_id = $user->ID;
        $user_statistic = $st->get_user_info($user_id);
        $will_busy_days = $user_statistic['countdown_days'] ? $st::ru_months_days($user_statistic['countdown_days']) : 0;
        $user_statistic['author'] = Did_User::getAllMyPosts($user_id);
        $is_free = $dUser->is_free($user_id);

        // Получим прогресс и статистику категорий и тэгов
        $progress = $st->get_div_studying_progress($user_id);
        $category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value', 'desc')->get(3);
        $tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value', 'desc')->max();

        $author_info = get_userdata($user_id);
        $author_info->inner_passing_rating = Did_Statistic::getSummOfTheInnerRatingByUser($user_id);
        $favorite_post_ids = $st->get_knowledges($user_id);
        $enable_link = true;

        $tasks_counters = array(
            'in_progress' => $user_statistic['in_progress'],
            'overdue' => $user_statistic['overdue_tasks'],
        );

        return compact(
            'user_statistic',
            'category_statistic',
            'author_info',
            'tag_statistic',
            'user_id',
            'dPost',
            'favorite_post_ids',
            'will_busy_days',
            'enable_link'
        );
    }

    public static function viewCabinet2Params($author) {
        global $dPost;

        $st = (new Did_Statistic)->oldStatisticClass;

        $user_id = $author->ID;

        $user_statistic = $st->get_user_info($user_id);
        $user_statistic['author'] = Did_User::getAllMyPosts($user_id);

        $favorite_post_ids = $st->get_knowledges($user_id);

        $category_statistic = $tag_statistic = array();
        $Did_Categories = new Did_Categories();
        $category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value', 'desc')->get(3);
        $tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value', 'desc')->max();

        $will_busy_days = $user_statistic['countdown_days'] ? $st::ru_months_days($user_statistic['countdown_days']) : 0;

        $user_info = $author_info = get_userdata($user_id);
        $author_info->inner_passing_rating = Did_Statistic::getSummOfTheInnerRatingByUser($user_id);

        return compact('user_statistic','category_statistic', 'author_info', 'tag_statistic', 'user_id', 'dPost', 'favorite_post_ids','will_busy_days');
    }

    public static function viewCabinetParams() {
        global  $dPost, $st;

        $author_info = wp_get_current_user();
        $user_id = $author_info->ID;

        $user_statistic = $st->get_user_info();
        $user_statistic['author'] = Did_User::getAllMyPosts($user_id);

        $will_busy_days = $user_statistic['countdown_days'] ? $st::ru_months_days($user_statistic['countdown_days']) : 0;
        
        $author_info->inner_passing_rating = Did_Statistic::getSummOfTheInnerRatingByUser($user_id);

        // get categories information by user
        $Did_Categories = new Did_Categories();
        $category_statistic = $tag_statistic = array();
        $category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value','desc')->get(3);
        $tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value','desc')->max();

        return compact(
            'user_statistic',
            'category_statistic', 
            'author_info', 
            'tag_statistic', 
            'user_id', 
            'dPost', 
            'favorite_post_ids',
            'will_busy_days'
        );
    }


    public static function getParamsForView($view, $params = []) {
        $method_name = str_replace(array('.', '-'), ' ', $view);
        $method_name = ucwords($method_name);
        $method_name = 'view' . str_replace(array(' ', '  '), '', $method_name) . 'Params';

        if(method_exists(__CLASS__, $method_name)) {
            return call_user_func_array(array(__CLASS__, $method_name), $params);
        }

        return null;
    }
}