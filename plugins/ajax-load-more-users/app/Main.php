<?php

namespace AjaxLoadMoreUsers\App;

use AjaxLoadMoreUsers\App\AdminInterface\Pages;

class Main {
    public function init() 
    {
        if (!class_exists('AjaxLoadMore')) 
        {
            return;
        }

        $this->init_language();
        $this->add_shortcode();

        // Add assets
        add_action('wp_enqueue_scripts', [$this, 'add_client_assets'], 100);
        add_action('wp_enqueue_scripts', [$this, 'localize_client_assets'], 100);

        // Ajax actions
        $this->add_ajax_actions();

        $this->init_admin_interface();
    }

    private function init_admin_interface() 
    {
        $SettingsModel = new Pages\Models\Settings;

        $SettingsController = new Pages\Controllers\Settings($SettingsModel);
        $SettingsView = new Pages\Views\Settings($SettingsController, $SettingsModel);
        
        $SettingsController->set_view($SettingsView);

        add_action('admin_menu', [$SettingsController, 'register_page'], 10);
    }

    /**
     * Loop over availaible actions and create instans for every Action
     * Then add private and global hooks
     * 
     * @return null
     */
    private function add_ajax_actions() 
    {
        $ajax_actions = array(
            'LoadMoreUsers' => 'almu_load_more_users',
        );

        foreach ($ajax_actions as $class_name => $action_alias) {
            $action_callable = __NAMESPACE__ . '\\AjaxActions\\' . $class_name;
            $Action = new $action_callable;

            add_action('wp_ajax_' . $action_alias, array($Action, 'execute'));
            add_action('wp_ajax_nopriv_' . $action_alias, array($Action,'execute'));
        }
    }

    /**
     * Init plugin shortcodes
     * 
     * @return null
     */
    public function add_shortcode() 
    {
        $LoadUsersShortcode = new Shortcodes\AjaxLoadMoreUsers;

        add_shortcode('ajax_load_more_users', [$LoadUsersShortcode, 'shortcode']);
    }

    /**
     * Load language files
     * 
     * @return null
     */
    private function init_language() 
    {
        $languages_folder = \AjaxLoadMoreUsers\PLUGIN_FOLDER . '/languages/';

        load_plugin_textdomain('ajax-load-more-users', false, $languages_folder);
    }

    /**
     * Add styles and scripts
     * 
     * @return null
     */
    public function add_client_assets() 
    {
        $url = plugins_url('/assets/', \AjaxLoadMoreUsers\PLUGIN_FILE);

        wp_enqueue_script(
            'almu-script', 
            $url . 'almu-main.js',
            ['jquery'],
            '2018-02-11',
            true
        );

        wp_enqueue_style(
            'almu-style', 
            $url . 'almu-main.css', 
            '2018-02-10'
        );
    }

    /**
     * Pass ajax url and nonce to script
     * 
     * @return null
     */
    public function localize_client_assets() 
    {
        wp_localize_script('almu-script', 'l10n', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('almu_nonce'),
        ]);
    }
}