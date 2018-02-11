<?php

class Did_UserQuery
{
    const USERS_PER_PAGE = 10;

    /**
     * Generate and return WP_User_Query object.
     * 
     * @param Array $args Arguments for WP_User_Query
     * @return object WP_User_Query
     */
    public static function getUserQuery(Array $args = array()) {
        $current_page = max(get_query_var('paged'), 1);
        $offset = self::USERS_PER_PAGE * ($current_page - 1);

        $default_args  = array(
            'number' => self::USERS_PER_PAGE,
            'paged' => $current_page,
            'offset' => $offset,
        );

        $query_args = array_merge($default_args, $args);

        // Create the WP_User_Query object
        $UserQuery = new WP_User_Query($query_args);

        return $UserQuery;
    }
}