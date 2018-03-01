<?php

use AjaxLoadMoreUsers\App\UserQuery as Almu_UserQuery;

class Did_Users
{
    /**
     * Gets and returns WP_User_Query object.
     * 
     * @param Array $args Arguments for WP_User_Query
     * @return object WP_User_Query
     */
    public static function getUsersQuery(Array $args = array()) 
    {
        $UserQueryWrapper = new Almu_UserQuery\Wrapper();

        return $UserQueryWrapper->getUsersQuery($args);
    }
}
