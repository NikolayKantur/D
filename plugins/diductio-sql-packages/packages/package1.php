<?php
$package_name = 'Пакет 57';
$package_description = 'Переименовать шаблоны в соответствии с кодексом WP';

return array(
    'title' => $package_name,
    'descr' => $package_description,
    '_exec' => function($params = array()) {
        $templates = array(
            'page-user-comments.php' => 'templates/template-user-comments.php',
            'page-people.php' => 'templates/template-people.php',
            'page-my-posts.php' => 'templates/template-my-posts.php',
            'page-progress.php' => 'templates/template-progress.php',
            'nowstudies.php' => 'templates/template-nowstudies.php',
            'page-all.php' => 'templates/template-all.php',
            'page-subscribers.php' => 'templates/template-subscribers.php',
            'page-allauthors.php' => 'templates/template-authors.php',
            'page-group.php' => 'templates/template-group.php',
            'page-istochniki.php' => 'templates/template-sources.php',
            'page-arrays.php' => 'templates/template-arrays.php',
        );

        global $wpdb;
        foreach ($templates as $old => $new) {
            $query = 'UPDATE wp_postmeta SET meta_value = "'.$new.'" WHERE meta_key = "_wp_page_template" AND meta_value="' . $old . '"';
            $wpdb->query($query);
        }
    }
);