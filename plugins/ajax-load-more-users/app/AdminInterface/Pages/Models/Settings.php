<?php

namespace AjaxLoadMoreUsers\App\AdminInterface\Pages\Models;

class Settings 
{
    public function getPageParams() 
    {
        return array(
            'menu_title' => __('- Users', 'ajax-load-more-users'),
            'page_title' => __('Ajax Load More Users', 'ajax-load-more-users'),

            'slug' => 'almu-settings',
            'parent' => 'ajax-load-more',

            'capability' => 'edit_dashboard',
        );
    }
}
