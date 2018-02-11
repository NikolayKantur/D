<?php

namespace AjaxLoadMoreUsers\App\AjaxActions;

class LoadMoreUsers {
    const USERS_PER_PAGE = 10;

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

        // Build query arguments array
        $args = array(
            'number' => self::USERS_PER_PAGE,
            'offset' => $offset,
            'paged' => $current_page,
        );

        // Set roles, if required
        if($_POST['roles']) {
            $args['role__in'] = filter_var($_POST['roles'], FILTER_SANITIZE_STRING);
        }

        // Set include
        if($_POST['include']) {
            $args['include'] = filter_var($_POST['include'], FILTER_SANITIZE_STRING);
        }

        // Instance query
        $UserQuery = new \WP_User_Query($args);

        // Create layout and set it to response
        ob_start();
        foreach ($UserQuery->results as $user) {
            set_query_var('user', $user);
            get_template_part('content', 'peoples');
        }
        $response['data']['layout'] = ob_get_clean();

        $total_users = $UserQuery->get_total();
        $total_pages = ceil($total_users / self::USERS_PER_PAGE);

        // IF all users are grabbed
        if($current_page >= $total_pages || !count($UserQuery->results)) {
            $response['data']['done'] = true;
        }

        echo json_encode($response);
        wp_die();
    }
}