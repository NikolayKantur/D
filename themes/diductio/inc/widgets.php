<?php
// (2) Модификация виджета Мета
function diductio_remove_meta_widget()
{
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
    unregister_widget('WP_Widget_Recent_Comments');
    register_widget('WP_Widget_Recent_Comments_Mod');
}

add_action('widgets_init', 'diductio_remove_meta_widget');

class WP_Widget_Meta_Mod extends
    WP_Widget
{
    
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_meta',
            'description' => __("Login, RSS, &amp; WordPress.org links."),
        );
        parent::__construct('meta', __('Meta'), $widget_ops);
    }
    
    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Meta') : $instance['title'],
            $instance, $this->id_base);
        
        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if (is_user_logged_in()) {
            global $st, $dUser, $dPost;
            $user_ID = get_current_user_id();
            $user_statistic = $st->get_user_info($user_ID);
            $comments_count = $dUser->get_comments_count($user_ID);
            $subscription_count = count(Did_User::getUserSubscription($user_id));
            $progress_percent = $st->get_knowledges($user_ID, 'active');
            $percent = 0;
            if ($progress_percent) {
                $tmp_precent = 0;
                foreach ($progress_percent as $item) {
                    $tmp_precent += $st->get_user_progress_by_post($item, $user_ID);
                }
                $percent = round($tmp_precent / count($progress_percent), 2);
            }
            
            //Get knowledges
            $post_ids = $st->get_knowledges($user_ID, 'active');
            
            // get user progress            
            $post_per_page     = wpfp_get_option("post_per_page");            
            $knowledges = [];
            if($post_ids) {
                $qry = array(                    
                    'posts_per_page' => $post_per_page,
                    'orderby' => 'post__in',
                    'paged' => $page,
                    'post__in' => $post_ids,
                    'post_status' => array( 'any' )
                );
                $knowledges = get_posts($qry, ARRAY_A);
            }
        }
        diductio_view('widgets/my-progress', compact('args', 'title', 'user_ID', 'user_statistic', 'percent', 'knowledges', 'st'));
        echo $args['after_widget'];
    }
    
    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        return $instance;
    }
    
    /**
     * @param array $instance
     */
    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = strip_tags($instance['title']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input
                class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>" type="text"
                value="<?php echo esc_attr($title); ?>"/></p>
        <?php
    }
}

// (21) Редактирование виджета "Свежие комментарии"


/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Recent_Comments_Mod extends
    WP_Widget
{
    
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_recent_comments',
            'description' => __('Your site&#8217;s most recent comments.'),
        );
        parent::__construct('recent-comments', __('Recent Comments'), $widget_ops);
        $this->alt_option_name = 'widget_recent_comments';
        
        if (is_active_widget(false, false, $this->id_base)) {
            add_action('wp_head', array($this, 'recent_comments_style'));
        }
        
        add_action('comment_post', array($this, 'flush_widget_cache'));
        add_action('edit_comment', array($this, 'flush_widget_cache'));
        add_action('transition_comment_status', array($this, 'flush_widget_cache'));
    }
    
    /**
     * @access public
     */
    public function recent_comments_style()
    {
        /**
         * Filter the Recent Comments default widget styles.
         *
         * @since 3.1.0
         * @param bool   $active Whether the widget is active. Default true.
         * @param string $id_base The widget ID.
         */
        if (!current_theme_supports('widgets') // Temp hack #14876
            || !apply_filters('show_recent_comments_widget_style', true, $this->id_base)
        ) {
            return;
        }
        ?>
        <style type="text/css">.recentcomments a {
                display: inline !important;
                padding: 0 !important;
                margin: 0 !important;
            }</style>
        <?php
    }
    
    /**
     * @access public
     */
    public function flush_widget_cache()
    {
        wp_cache_delete('widget_recent_comments', 'widget');
    }
    
    /**
     * @global array  $comments
     * @global object $comment
     * @param array   $args
     * @param array   $instance
     */
    public function widget($args, $instance)
    {
        if( !is_user_logged_in() ){
            
            global $comments, $wpdb;
            
            $cache = array();
            if (!$this->is_preview()) {
                $cache = wp_cache_get('widget_recent_comments', 'widget');
            }
            if (!is_array($cache)) {
                $cache = array();
            }
            
            if (!isset($args['widget_id'])) {
                $args['widget_id'] = $this->id;
            }
            
            if (isset($cache[$args['widget_id']])) {
                echo $cache[$args['widget_id']];
                
                return;
            }
            
            $output = '';
            
            $title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Comments');
            
            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);
            
            $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
            if (!$number) {
                $number = 5;
            }
            
            /**
             * Filter the arguments for the Recent Comments widget.
             *
             * @since 3.4.0
             * @see   WP_Comment_Query::query() for information on accepted arguments.
             * @param array $comment_args An array of arguments used to retrieve the recent comments.
             */
            
            /* Так как нельзя группировать то по Post_id пишем свой запрос
            $comments = get_comments( apply_filters( 'widget_comments_args', array(
                'number'      => 100,
                'status'      => 'approve',
                'post_status' => 'publish'
            ) ) );*/
            
            //SQL запрос, получаем интересующие записи
            $table_name = $wpdb->get_blog_prefix() . 'comments';
            $sql
                = "
              SELECT *
              FROM (
                SELECT *
                FROM `$table_name`
                ORDER BY `comment_date` DESC
              ) AS wp_comments
              WHERE `comment_approved` = 1
              GROUP BY wp_comments.comment_post_id
              ORDER BY wp_comments.comment_date DESC
              LIMIT $number";
            
            //это SQL запрос прогресса
            $table_name2 = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql2
                = "
              SELECT *
              FROM (
                SELECT *
                FROM `$table_name2`
                ORDER BY `update_at` DESC
              ) AS wp_progres
              WHERE wp_progres.update_at != '0000-00-00 00:00:00' 
              AND  wp_progres.checked_lessons != '0'
              GROUP BY  wp_progres.post_id
              ORDER BY  wp_progres.update_at DESC
              LIMIT $number";
            
            //выполняем запроссы
            $progress = $wpdb->get_results($sql2);
            $comments = $wpdb->get_results($sql);
            $stream = [];
            
            //формируем ленту
            if (is_array($comments) && $comments) {
                foreach ((array)$comments as $comment) {
                    
                    //print_r($comment);
                    $stream[] = Array(
                        'id' => $comment->comment_ID,
                        'post_id' => $comment->comment_post_ID,
                        'user_id' => $comment->user_id,
                        'update_at' => $comment->comment_date_gmt,
                        'content' => $comment->comment_content,
                    );
                }
            }
            
            if (is_array($progress) && $progress) {
                foreach ((array)$progress as $progres) {
                    $stream[] = Array(
                        'post_id' => $progres->post_id,
                        'user_id' => $progres->user_id,
                        'update_at' => $progres->update_at,
                        'content' => null,
                    );
                }
            }
            //сортируем по дате
            usort($stream, 'sort_desc');
            //обраезаем массив по колличеству из админки
            $stream_n = array_slice($stream, 0, $number);
            unset($stream);
            
            $output .= $args['before_widget'];
            if ($title) {
                $output .= $args['before_title'] . $title . $args['after_title'];
            }
            
            #stat stream
            $output .= '<ul id="recentcomments">';
            if (is_array($stream_n) && $stream_n) {
                
                foreach ((array)$stream_n as $s) {
                    $user_info = get_user_by('id', $s['user_id']);
                    $user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
                    
                    $output .= '<li class="recentcomments">';
                    $output .= "<div class='inline comment-avatar'><a href='{$user_link}'>";
                    $output .= get_avatar($user_info->data->user_email, 20);
                    $output .= "<span>";
                    $output .= $user_info->data->display_name;
                    
                    if ($s['content'] === null) {
                        $small_text = "+ прогресс";
                    } else {
                        $comments_count = wp_count_comments($s['post_id']);
                        $approved = $comments_count->approved;
                        
                        $small_text = "+ комментарий";
                    }
                    
                    $output .= "</span></a><small>" . $small_text . "</small></div>";
                    $output .= "<div class='inline comment-content'>";
                    $output .= "<div class='comment-body'>";
                    if ($s['content'] != null) {
                        $output .= diductio_excerp_comment(get_comment($s['id'])->comment_content, 67);
                        $output .= "<a class='link-style-1' href='"
                            . esc_url(get_comment_link($s['id'])) . "'>&nbsp;#</a><br>";
                        $output .= sprintf(_x('%1$s', 'widgets'), ' <a class="link-style-1" href="'
                            . esc_url(get_permalink($s['post_id'])) . '"> '
                            . get_the_title($s['post_id']) . '</a>');
                    } else {
                        $output .= sprintf(_x('%1$s', 'widgets'), ' <a class="link-style-1" href="'
                            . esc_url(get_permalink($s['post_id'])) . '"> '
                            . get_the_title($s['post_id']) . '</a>');
                    }
                    $output .= "<span></span></div>";
                    $output .= "</div>";
                    $output .= "</li>";
                }
            }
            $output .= '</ul>';
            ##end stream
            $output .= $args['after_widget'];
            echo $output;
            
            if (!$this->is_preview()) {
                $cache[$args['widget_id']] = $output;
                wp_cache_set('widget_recent_comments', $cache, 'widget');
            }
        
        }
        
    }
    
    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $this->flush_widget_cache();
        
        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_recent_comments'])) {
            delete_option('widget_recent_comments');
        }
        
        return $instance;
    }
    
    /**
     * @param array $instance
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        
        <p><label
                for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>"
                   size="3"/></p>
        <?php
    }
}