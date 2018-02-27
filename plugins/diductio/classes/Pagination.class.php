<?php

class Did_Pagination
{
    public static function getPaginationForSubscribers($UserQuery) {
        $slug = basename(get_permalink());
        $paginate_url =  home_url()."/{$slug}/page/%#%";

        $total_pages = ceil($UserQuery->get_total() / Did_User::USERS_PER_PAGE);

        return paginate_links(array(
            'base' => $paginate_url, // the base URL, including query arg
            'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
            
            'prev_text' => __('&laquo; Previous'), // text for previous page
            'next_text' => __('Next &raquo;'), // text for next page
            'total' => $total_pages, // the total number of pages we have
            'current'  => max(1, get_query_var('paged')),
            'end_size' => 1,
            'mid_size' => 5,
            'prev_next' => true,
        ));
    }

    public static function getPaginationForUserComments($user_id) {
        $page = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $user_comments_total = get_comments(array(
            'orderby'    => 'post_date',
            'order'      => 'DESC',
            'author__in' => $user_id,
            'status'     => 'approve',
        ));
        $pages = ceil(count($user_comments_total) / Did_User::COMMENTS_PER_PAGE);

        return paginate_links(array(
            'format'    => 'page/%#%',
            'total'     => $pages,
            'current'   => $page,
            'show_all'  => false,
            'end_size'  => 1,
            'mid_size'  => 2,
            'prev_next' => true,
            'prev_text' => __('Previous'),
            'next_text' => __('Next'),
            'type'      => 'plain',
        ));
    }

    public static function getPaginationForMutualSubscribes($UserQuery) {
        if(!$UserQuery) {
            return null;
        }

        $page = max(1, get_query_var('paged'));
        $total_pages = ceil($UserQuery->get_total() / Did_User::USERS_PER_PAGE);

        $slug = basename(get_permalink());
        $paginate_url =  "/{$slug}/page/";

        return paginate_links(array(
            'base' => $paginate_url . '%#%',
            'format' => '&p=%#%', 
            'prev_text' => __('&laquo; Previous'),
            'next_text' => __('Next &raquo;'),
            'total' => $total_pages,
            'current'  => $page,
            'end_size' => 1,
            'mid_size' => 5,
        ));
    }

    public static function getPaginationForSources() {
        global $wpdb;
        $paginate_url = home_url()."/source/";

        $sql = "SELECT term_id FROM (SELECT wp_term_relationships.term_taxonomy_id AS tagid, substr(wp_posts.post_date_gmt,1,10) AS tagdate FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id INNER JOIN wp_posts ON wp_posts.ID=wp_term_relationships.object_id WHERE taxonomy='post_tag' ORDER BY post_date_gmt DESC, wp_posts.post_title) AS tag_history INNER JOIN wp_terms ON wp_terms.term_id=tag_history.tagid GROUP BY tag_history.tagid ORDER BY tag_history.tagdate DESC";
        $tag_count = ceil(count($wpdb->get_results($sql))/10);

        return paginate_links(array(
            'base'         => $paginate_url.'%#%',
            'total'        => $tag_count,
            'current'      => max(1, get_query_var('page')),
            'show_all'     => False,
            'end_size'     => 1,
            'mid_size'     => 2,
            'prev_next'    => True,
            'prev_text'    => __('« Previous'),
            'next_text'    => __('Next »'),
            'type'         => 'list',
            'add_args'     => False,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        ));
    }

    public static function getPaginationForArrays() {
        if(is_page('array-recently')) {
            $courses = diductio_get_courses();
            $paginate_url = home_url()."/array-recently/";
        } elseif ('array-active') {
            $courses = diductio_get_courses(false);
            $paginate_url = home_url()."/array-active/";
        }

        $page_count = ceil(count($courses)/10);

        return paginate_links( array(
            'base'         => $paginate_url . '%#%',
            'total'        => $page_count,
            'current'      => max(1,get_query_var('page')),
            'show_all'     => False,
            'end_size'     => 1,
            'mid_size'     => 2,
            'prev_next'    => True,
            'prev_text'    => __('« Previous'),
            'next_text'    => __('Next »'),
            'type'         => 'list',
            'add_args'     => False,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        ) );
    }
}