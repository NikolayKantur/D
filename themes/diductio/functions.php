<?php

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';


// Include customs
require get_template_directory() . '/inc/autoload.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/assets.php';
require get_template_directory() . '/inc/utils.php';
require get_template_directory() . '/inc/shortcodes.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/extras.php';


//init ajax
$Did_static = new Did_Statistic();
$dCommenst = new Did_Comments();
$Did_static->initAjax();