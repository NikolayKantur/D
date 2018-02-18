<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if (!isset($content_width)) {
    $content_width = 660;
}

//Global variables
$view_path = get_stylesheet_directory() . "/view/";
$data = new stdClass();

//theme configuration
$settings = array();
$settings['stat_table'] = $wpdb->get_blog_prefix() . 'user_add_info';
$stat_count = $wpdb->get_row("SELECT COUNT(`id`) AS `count` FROM `{$settings['stat_table']}`");
$settings['stat_table_count'] = $stat_count->count;
$settings['view_path'] = get_stylesheet_directory() . "/view/";
$settings['post_formats_slug'] = array(
    'post-format-aside',
    'post-format-chat',
    'post-format-gallery',
    'post-format-image',
);
unset($stat_count);
$diductio = Diductio::gi();
$diductio->settings = $settings;
$dPost = new Post();
$dUser = new User_old();
$st = (new Did_Statistic)->oldStatisticClass;

Diductio::gi()->post = $dPost;
Diductio::gi()->user = $dUser;
Diductio::gi()->statistic = $st;

if (is_admin()) {
    $file_name = 'classes/Admin.class.php';
    $admin_file = get_template_directory() . DIRECTORY_SEPARATOR . $file_name;
    if (file_exists($admin_file)) {
        require_once($admin_file);
    }
}

add_action('embed_head', array($diductio, 'includeStyles'));



function diductio_widgets_init()
{
    register_sidebar(array(
        'name' => __('Widget Area', 'diductio'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar.', 'diductio'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Header', 'diductio'),
        'id' => 'sidebar-header',
        'description' => __('Add widgets here to appear in your header.', 'diductio'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
}

add_action('widgets_init', 'diductio_widgets_init');


/**
 * Sets up theme defaults and registers support for various WordPress features.
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Fifteen 1.0
 */
function diductio_setup()
{
    load_theme_textdomain('diductio');
    
    add_theme_support('automatic-feed-links');
    
    add_theme_support('title-tag');
    
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(825, 510, true);
    
    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
        'main' => 'Меню в верхней панели',
        'primary' => __('Primary Menu', 'diductio'),
        'social' => __('Social Links Menu', 'diductio'),
    ));
    
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ));
    
    add_theme_support('custom-logo', array(
        'height' => 248,
        'width' => 248,
        'flex-height' => true,
    ));
    
    $color_scheme = diductio_get_color_scheme();
    $default_color = trim($color_scheme[0], '#');
    
    // Setup the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('diductio_custom_background_args', array(
        'default-color' => $default_color,
        'default-attachment' => 'fixed',
    )));
    
    /*
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
    add_editor_style(array('css/editor-style.css', 'genericons/genericons.css', diductio_fonts_url()));
   
    // Indicate widget sidebars can use selective refresh in the Customizer.
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'diductio_setup');

