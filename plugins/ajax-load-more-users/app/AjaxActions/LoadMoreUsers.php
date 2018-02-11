<?php

namespace AjaxLoadMoreUsers\App\AjaxActions;

class LoadMoreUsers {
    const USERS_PER_PAGE = 10;

    private $allowed_args = ['include', 'roles', 'orderby', 'order'];

    public function execute() 
    {
        $response = [
            'status' => 'error', 
            'message' => '',
        ];

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'almu_nonce')) {
            echo json_encode($response);
            wp_die();
        }

        // Set additional params to response
        $response['status'] = 'success';
        $response['data'] = [
            'done' => false,
        ];

        // Get current page and other query args
        $current_page = $_POST['current_page'];
        if (!is_numeric($current_page)) {
            $current_page = 2;
        }
        
        $offset = ($current_page - 1) * self::USERS_PER_PAGE;
        
        // Build default and user arguments
        $default_args = array(
            'number' => self::USERS_PER_PAGE,
            'offset' => $offset,
            'paged' => $current_page,

            'orderby' => 'post_count',
            'order' => 'DESC',
        );
        $user_args = $this->fill_arguments_from($_POST);

        // Build query arguments array
        $query_args = array_merge($default_args, $user_args);

        // Instance query
        $UserQuery = new \WP_User_Query($query_args);

        // Create layout and set it to response
        ob_start();
        foreach ($UserQuery->results as $user) {
            set_query_var('user', $user);
            get_template_part('content', 'peoples');
        }
        $response['data']['layout'] = ob_get_clean();

        // Get total pages count
        $total_users = $UserQuery->get_total();
        $total_pages = ceil($total_users / self::USERS_PER_PAGE);

        // If all users are grabbed or no results more
        if($current_page >= $total_pages || !count($UserQuery->results)) {
            $response['data']['done'] = true;
        }

        echo json_encode($response);
        wp_die();
    }

    /**
     * Set existing arguments up from passed array, 
     * and returns it. Non-allowed arguments are ignored.
     * 
     * @param array $user_arguments 
     * @return array
     */
    private function fill_arguments_from(Array $user_arguments) {
        $args = [];
        
        foreach ($user_arguments as $arg_alias => $arg_value) {
            if(!in_array($arg_alias, $this->allowed_args) || !$arg_value) {
                continue;
            }

            $sanitized_value = filter_var($arg_value, FILTER_SANITIZE_STRING);
            $args[$arg_alias] = $sanitized_value;
        }

        return $args;
    }
}