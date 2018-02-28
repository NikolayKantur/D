<?php
// (2) Модификация виджета Мета
function diductio_remove_meta_widget()
{
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
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