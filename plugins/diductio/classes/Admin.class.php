<?php

class Did_Admin extends Diductio
{
    /**
     * Admin class constructor.
     */
    function __construct()
    {
        $this->addAction();
    }

    /**
     * Функция запуска хуков-действий
     */
    function addAction()
    {
        //добавление скриптов
        wp_enqueue_script('diductio-admin', get_template_directory_uri() . '/admin/js/script.js', array('jquery'),
                1.0, true);
        wp_localize_script('diductio-admin', 'ajax_path', array('url' =>admin_url('admin-ajax.php')));
    }
}