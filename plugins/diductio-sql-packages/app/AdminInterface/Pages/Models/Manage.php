<?php

namespace DiductioSqlPackages\App\AdminInterface\Pages\Models;

class Manage 
{
    public function getPageParams() 
    {
        return array(
            'menu_title' => __('Diductio SQL Пакеты', 'ajax-load-more-users'),
            'page_title' => __('SQL Packages', 'ajax-load-more-users'),

            'slug' => 'dsp-packages',
            'parent' => 'options-general.php',

            'capability' => 'edit_dashboard',
        );
    }
}
