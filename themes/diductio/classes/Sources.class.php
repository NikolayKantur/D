<?php

class Did_Sources
{
    public static function getSources() {
        global $wpdb;

        $from = 0;
        $to = get_option( 'posts_per_page' ); 

        $current_page = get_query_var('page');
        if($current_page) {
            $from = ($current_page - 1) * get_option( 'posts_per_page' );
            $to = $current_page * get_option( 'posts_per_page' );
        }

        $sql = "SELECT term_id, name, slug, tag_history.tagdate FROM (SELECT wp_term_relationships.term_taxonomy_id AS tagid, substr(wp_posts.post_date_gmt,1,10) AS tagdate FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id INNER JOIN wp_posts ON wp_posts.ID=wp_term_relationships.object_id WHERE taxonomy='post_tag' ORDER BY post_date_gmt DESC, wp_posts.post_title) AS tag_history INNER JOIN wp_terms ON wp_terms.term_id=tag_history.tagid GROUP BY tag_history.tagid ORDER BY tag_history.tagdate DESC LIMIT {$from},{$to}";
        $progress = $wpdb->get_results($sql);

        return $progress;
    }
    
}