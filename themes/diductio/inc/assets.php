<?php

function diductio_fonts_url()
{
    $fonts_url = '';
    $fonts = array();
    $subsets = 'latin,latin-ext';

    /*
     * Translators: If there are characters in your language that are not supported
     * by Noto Sans, translate this to 'off'. Do not translate into your own language.
     */
    if ('off' !== _x('on', 'Noto Sans font: on or off', 'diductio')) {
        $fonts[] = 'Noto Sans:400italic,700italic,400,700';
    }

    /*
     * Translators: If there are characters in your language that are not supported
     * by Noto Serif, translate this to 'off'. Do not translate into your own language.
     */
    if ('off' !== _x('on', 'Noto Serif font: on or off', 'diductio')) {
        $fonts[] = 'Noto Serif:400italic,700italic,400,700';
    }

    /*
     * Translators: If there are characters in your language that are not supported
     * by Inconsolata, translate this to 'off'. Do not translate into your own language.
     */
    if ('off' !== _x('on', 'Inconsolata font: on or off', 'diductio')) {
        $fonts[] = 'Inconsolata:400,700';
    }

    /*
     * Translators: To add an additional character subset specific to your language,
     * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
     */
    $subset = _x('no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'diductio');

    if ('cyrillic' == $subset) {
        $subsets .= ',cyrillic,cyrillic-ext';
    } elseif ('greek' == $subset) {
        $subsets .= ',greek,greek-ext';
    } elseif ('devanagari' == $subset) {
        $subsets .= ',devanagari';
    } elseif ('vietnamese' == $subset) {
        $subsets .= ',vietnamese';
    }

    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family' => urlencode(implode('|', $fonts)),
            'subset' => urlencode($subsets),
        ), 'https://fonts.googleapis.com/css');
    }

    return $fonts_url;
}

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function diductio_scripts()
{
    Diductio::includeStyles();
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('diductio-fonts', diductio_fonts_url(), array(), null);
    
    // Add Genericons, used in the main stylesheet.
    wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2');
    
    // Load our main stylesheet.
    wp_enqueue_style('diductio-deprecated-style', get_stylesheet_uri());
    
    // Load the Internet Explorer specific stylesheet.
    wp_enqueue_style('diductio-ie', get_template_directory_uri() . '/css/ie.css', array('diductio-deprecated-style'),
        '20141010');
    wp_style_add_data('diductio-ie', 'conditional', 'lt IE 9');
    
    // Load the Internet Explorer 7 specific stylesheet.
    wp_enqueue_style('diductio-ie7', get_template_directory_uri() . '/css/ie7.css',
        array('diductio-deprecated-style'), '20141010');
    wp_style_add_data('diductio-ie7', 'conditional', 'lt IE 8');
    
    wp_enqueue_script('diductio-skip-link-focus-fix',
        get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    if (is_singular() && wp_attachment_is_image()) {
        wp_enqueue_script('diductio-keyboard-image-navigation',
            get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
    }
    
    wp_enqueue_script('diductio-script', get_template_directory_uri() . '/js/functions.js', array('jquery'),
        '20150330', true);
    wp_localize_script('diductio-script', 'screenReaderText', array(
        'expand' => '<span class="screen-reader-text">' . __('expand child menu', 'diductio') . '</span>',
        'collapse' => '<span class="screen-reader-text">' . __('collapse child menu', 'diductio') . '</span>',
    ));


    // (13 глобальный) Опции JS по умолчанию
    $didaction_object = array(
        'child_theme_url' => get_stylesheet_directory_uri(),
        'ajax_path' => admin_url('admin-ajax.php'),
    );
    
   
    wp_enqueue_script('diductio-script-2', get_stylesheet_directory_uri() . "/js/javascripts.js", ['jquery'], false, true);

    wp_localize_script('diductio-script-2', 'diductioObject', $didaction_object);
    
    // (14) Подключение bootstrap
    wp_register_script('diductio-bootstrap-js',
        "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js", ['jquery-ui-core'], false, true);
    wp_enqueue_script('diductio-bootstrap-js');
    
    // (14) Подключение bootstrap end
}

add_action('wp_enqueue_scripts', 'diductio_scripts');

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 * @see   wp_add_inline_style()
 */
function diductio_post_nav_background()
{
    if (!is_single()) {
        return;
    }
    
    $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
    $next = get_adjacent_post(false, '', false);
    $css = '';
    
    if (is_attachment() && 'attachment' == $previous->post_type) {
        return;
    }
    
    if ($previous && has_post_thumbnail($previous->ID)) {
        $prevthumb = wp_get_attachment_image_src(get_post_thumbnail_id($previous->ID), 'post-thumbnail');
        $css
            .= '
            .post-navigation .nav-previous { background-image: url(' . esc_url($prevthumb[0]) . '); }
            .post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
            .post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
        ';
    }
    
    if ($next && has_post_thumbnail($next->ID)) {
        $nextthumb = wp_get_attachment_image_src(get_post_thumbnail_id($next->ID), 'post-thumbnail');
        $css
            .= '
            .post-navigation .nav-next { background-image: url(' . esc_url($nextthumb[0]) . '); border-top: 0; }
            .post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
            .post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
        ';
    }
    
    wp_add_inline_style('diductio-inline-style', $css);
}

add_action('wp_enqueue_scripts', 'diductio_post_nav_background');

function diductio_add_styles() {
    wp_enqueue_style(
        'diductio-style', 
        get_template_directory_uri() . '/dist/styles/main.css', 
        array('diductio-deprecated-style'),
        '2018-03-02'
    );
}
add_action('wp_enqueue_scripts', 'diductio_add_styles', 100);