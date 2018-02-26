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

        $this->initLanguage();
        $this->addShortcode();

        // Add assets
        add_action('wp_enqueue_scripts', [$this, 'addClientAssets'], 100);
        add_action('wp_enqueue_scripts', [$this, 'localizeClientAssets'], 100);

        // Ajax actions
        $this->addAjaxActions();

        $this->initAdminInterface();
    }

    private function initAdminInterface() 
    {
        $SettingsModel = new Pages\Models\Settings;

        $SettingsController = new Pages\Controllers\Settings($SettingsModel);
        $SettingsView = new Pages\Views\Settings($SettingsController, $SettingsModel);
        
        $SettingsController->setView($SettingsView);

        add_action('admin_menu', [$SettingsController, 'registerPage'], 10);
    }

    /**
     * Loop over availaible actions and create instans for every Action
     * Then add private and global hooks
     * 
     * @return null
     */
    private function addAjaxActions() 
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
    public function addShortcode() 
    {
        $LoadUsersShortcode = new Shortcodes\AjaxLoadMoreUsers;

        add_shortcode('ajax_load_more_users', [$LoadUsersShortcode, 'shortcode']);
    }

    /**
     * Load language files
     * 
     * @return null
     */
    private function initLanguage() 
    {
        $languages_folder = \AjaxLoadMoreUsers\PLUGIN_FOLDER . '/languages/';

        load_plugin_textdomain('ajax-load-more-users', false, $languages_folder);
    }

    /**
     * Add styles and scripts
     * 
     * @return null
     */
    public function addClientAssets() 
    {
        $url = plugins_url('/assets/', \AjaxLoadMoreUsers\PLUGIN_FILE);

        wp_enqueue_script(
            'almu-script', 
            $url . 'almu-main.js',
            ['jquery'],
            '2018-02-14',
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
    public function localizeClientAssets() 
    {
        wp_localize_script('almu-script', 'l10n', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('almu_nonce'),
        ]);
    }
}