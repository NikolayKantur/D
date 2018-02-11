<?php

namespace AjaxLoadMoreUsers\App\Shortcodes;

use AjaxLoadMoreUsers\App\Templates;

class AjaxLoadMoreUsers 
{
    /**
     * Render "Load More" button and return rendered content
     * 
     * @param mixed $user_attrs 
     * @return string
     */
    public function shortcode($user_attrs = null) 
    {
        $TemplatesRenderer = new Templates\Renderer;

        $default_attrs = array(
            'button_text' => __('Load More', 'ajax-load-more-users'),

            'roles' => array(),
            'include' => null,
            'orderby' => null,
            'order' => null,
            'per_page' => null,
        );

        $template_attrs = shortcode_atts($default_attrs, $user_attrs);

        return $TemplatesRenderer->render('load-more-button', $template_attrs);
    }
}
