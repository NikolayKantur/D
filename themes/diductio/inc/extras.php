<?php
/**
 * JavaScript Detection.
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Fifteen 1.1
 */
function diductio_javascript_detection()
{
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action('wp_head', 'diductio_javascript_detection', 0);

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 * @param string  $item_output The menu item output.
 * @param WP_Post $item Menu item object.
 * @param int     $depth Depth of the menu.
 * @param array   $args wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function diductio_nav_description($item_output, $item, $depth, $args)
{
    if ('primary' == $args->theme_location && $item->description) {
        $item_output = str_replace($args->link_after . '</a>',
            '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>',
            $item_output);
    }
    
    return $item_output;
}

add_filter('walker_nav_menu_start_el', 'diductio_nav_description', 10, 4);

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function diductio_search_form_modify($html)
{
    return str_replace('class="search-submit"', 'class="search-submit screen-reader-text"', $html);
}

add_filter('get_search_form', 'diductio_search_form_modify');


function diductio_entry_meta()
{
    if (is_sticky() && is_home() && !is_paged()) {
        printf('<span class="sticky-post">%s</span>', __('Featured', 'diductio'));
    }
    $format = get_post_format();
    if (current_theme_supports('post-formats', $format)) {
        printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
            sprintf('<span class="screen-reader-text">%s </span>',
                _x('Format', 'Used before post format.', 'diductio')),
            esc_url(get_post_format_link($format)),
            get_post_format_string($format)
        );
    }
    if ('post' == get_post_type()) {
        $categories_list = get_the_category_list(_x(', ',
            'Used between list items, there is a space after the comma.', 'diductio'));
        if ($categories_list && diductio_categorized_blog()) {
            printf('<span  class="cat-links 2"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Categories', 'Used before category names.', 'diductio'),
                $categories_list
            );
        }
        
        $tags_list = get_the_tag_list('',
            _x(', ', 'Used between list items, there is a space after the comma.', 'diductio'));
        if ($tags_list) {
            printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Tags', 'Used before tag names.', 'diductio'),
                $tags_list
            );
        }
    }
    if (is_attachment() && wp_attachment_is_image()) {
        // Retrieve attachment metadata.
        $metadata = wp_get_attachment_metadata();
        
        printf('<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
            _x('Full size', 'Used before full size attachment link.', 'diductio'),
            esc_url(wp_get_attachment_url()),
            $metadata['width'],
            $metadata['height']
        );
    }
}

// (20) Стилизация авторизации, регистрации, восстановления пароля, выхода
function diductio_custom_logo()
{
    echo '
   <style type="text/css">
        #login h1 a { background: url(' . get_stylesheet_directory_uri() . '/images/logo.png) no-repeat 0 0 !important; width:320px; height: 123px;    box-shadow: 0 1px 3px rgba(0,0,0,.13); }
    </style>';
}
add_action('login_head', 'diductio_custom_logo');

/* Ставим ссылку с логотипа на сайт, а не на wordpress.org */
add_filter('login_headerurl', create_function('', 'return get_home_url();'));

/* убираем title в логотипе "сайт работает на wordpress" */
add_filter('login_headertitle', create_function('', 'return false;'));


// (25) Стилизация "Читать далее"
add_action('the_content_more_link', 'diductio_read_more_customize', 10, 2);
function diductio_read_more_customize($link, $text)
{
    
    return str_replace(
        'more-link',
        'more-link link-style-1',
        $link
    );
}

// (26) Удаление "Рубрика", "Метка" из лент рубрик и источников
add_filter('get_the_archive_title', function ($title) {
    
    $title = str_replace(array('Рубрика:', 'Метка:'), '', $title);
    
    return $title;
});
// (26) Удаление "Рубрика", "Метка" из лент рубрик и источников end

// (27) Редирект на главную после после авторизации
function diductio_login_redirect($redirect_to, $request, $user)
{
    return home_url();
}

add_filter('login_redirect', 'diductio_login_redirect', 10, 3);
// (27) Редирект на главную после после авторизации end

// (28) Модификация личного кабинета
function diductio_remove_menus()
{
    global $current_user;
    wp_get_current_user();
    
    if (user_can($current_user, "subscriber")) {
        remove_menu_page('index.php');
    }
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    
}
add_action('admin_menu', 'diductio_remove_menus');

function diductio_init_function()
{
    global $current_user;
    wp_get_current_user();
    if (user_can($current_user, "subscriber")) {
        update_user_meta($current_user->ID, 'show_admin_bar_front', 'false');
    }
    add_post_type_support('post', 'custom-fields');
    
}

add_action('init', 'diductio_init_function');
// (28) Модификация личного кабинета end


add_filter('clean_url', 'diductio_js_front_end_defer', 11, 1);
function diductio_js_front_end_defer($url)
{
    if (false === strpos($url, '.js')) {
        return $url;
        
    }
    
    return "$url' defer='defer";
}

function diductio_my_tweaked_admin_bar()
{
    global $wp_admin_bar;
    
    // print_r($wp_admin_bar);
}

add_action('wp_before_admin_bar_render', 'diductio_my_tweaked_admin_bar');


add_filter('embed_defaults', 'diductio_bigger_embed_size');

function diductio_bigger_embed_size()
{
    if (wp_is_mobile()) {
        return array('width' => 780, 'height' => 200);
    } else {
        return array('width' => 780, 'height' => 430);
    }
}

//удаление поста из статистической таблицы пользователей
add_action('before_delete_post', 'diductio_course_removed');
function diductio_course_removed($postid)
{
    global $current_user, $wpdb;
    
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "DELETE FROM `wp_user_add_info` WHERE `post_id` = {$postid}";
    $wpdb->query($sql);
}

function diductio_myclass_edit_comment_link($output)
{
    $myclass = 'link-style-2';
    
    return preg_replace('/comment-edit-link/', 'comment-edit-link ' . $myclass, $output, 1);
}

add_filter('edit_comment_link', 'diductio_myclass_edit_comment_link');

add_filter('comment_form_defaults', 'diductio_sp_comment_form_defaults');
function diductio_sp_comment_form_defaults($defaults)
{
    
    $defaults['title_reply'] = "";
    $defaults['comment_field']
        = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="10" aria-required="true"></textarea></p>';
    
    return $defaults;
    
}


// (36) Изменение шаблонов
add_filter('template_include', 'diductio_my_template');
function diductio_my_template($template)
{
    
    // если это страница со слагом projjdennye-massivy, используем файл шаблона page-arrays.php
    // используем условный тег is_page()
    
    if (is_page('array-recently') || is_page('array-active')) {
        if ($new_template = locate_template(array('templates/template-arrays.php'))) {
            return $new_template;
        }
    } elseif (is_page('source')) {
        // если это страница со слагом istochniki(страница источников), используем файл шаблона page-istochiki
        // используем условный тег is_page()
        if ($new_template = locate_template(array('templates/template-sources.php'))) {
            return $new_template;
        }
    } else {
        return $template;
    }
}

// (36) Изменение шаблонова end


// (45) Стилизация количества записей в виджете категорий
function diductio_categories_postcount_filter($variable)
{
    $variable = str_replace('(', '<span class="label label-success right-count">', $variable);
    $variable = str_replace(')', '</span>', $variable);
    
    return $variable;
}

add_filter('wp_list_categories', 'diductio_categories_postcount_filter');
// (45) Стилизация количества записей в виджете категорий end


// (47) Связывание добавление и удаление в избранное с логикой зачётки
// add_action( 'post_updated', 'diductio_post_update_method', 10, 3 );
function diductio_post_update_method($post_ID, $post_after, $post_before)
{
    //method depricated - I've moved it to the post->onPostUpdate;
    global $wpdb;
    
    $words_array = str_word_count($post_after->post_content, 1);
    $accordion_count = 0;
    foreach ($words_array as $key => $value) {
        if ($value == 'accordion-item') {
            $accordion_count++;
        }
    }
    if ($accordion_count % 2 == 0) {
        $accordion_count = $accordion_count / 2;
    }
    
    if ($accordion_count == 0) {
        $accordion_count = get_post_meta($post_ID, 'publication_count', true);
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
    $sql .= " WHERE `post_id` = {$post_ID}";
    $wpdb->query($sql);
}

function diductio_remove_br_accordion($content)
{
    $array = array(
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']',
        ']<br>' => ']',
    );
    $content = strtr($content, $array);
    
    return $content;
}

add_filter('the_content', 'diductio_remove_br_accordion');


// add_action('save_post', 'diductio_add_post_to_statistic', 10, 3);
function diductio_add_post_to_statistic($post_id, $user_id = false)
{
    global $current_user, $wpdb;
    
    $user_id = $user_id ? $user_id : $current_user->ID;
    
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'post_id' => $post_id,
            'update_at' => "NOW()",
            'lessons_count' => 1,
            'checked_lessons' => 0,
            'added_by' => $current_user->ID,
        ),
        array(
            '%d',
            '%d',
            '%s',
            '%d',
            '%s',
            '%d',
        )
    );
}

add_action('wpfp_after_remove', 'diductio_remove_post_from_statistic');
function diductio_remove_post_from_statistic($post_id)
{
    global $current_user, $wpdb;
    $user_id = $current_user->ID;
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    
    $wpdb->delete($table_name, array('user_id' => $user_id, 'post_id' => $post_id));
}

// (47) Связывание добавление и удаление в избранное с логикой зачётки end


function diductio_my_is_protected_meta_filter($protected, $meta_key)
{
    if ($meta_key == 'old_id' || $meta_key == 'wpfp_favorites') {
        return true;
    } else {
        return $protected;
    }
}
add_filter('is_protected_meta', 'diductio_my_is_protected_meta_filter', 10, 2);