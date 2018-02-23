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
        add_action('admin_init', array($this, 'diductio_settings_init'));
        // регистрация страницы настроек дидактио
        add_action('admin_menu', function () {
            add_menu_page(
                'Настройки Diductio', 
                'Diductio Settings', 
                'manage_options', 
                'diductio-options',
                    Array($this, 'build_settings_page'), '', 81
            );
        });

        //добавление скриптов
        wp_enqueue_script('diductio-admin', get_template_directory_uri() . '/admin/js/script.js', array('jquery'),
                1.0, true);
        wp_localize_script('diductio-admin', 'ajax_path', array('url' =>admin_url('admin-ajax.php')));

        //регистрация ajax функций
        add_action('wp_ajax_stat_recount', array($this, 'stat_recount'));
    }

    /**
     * Функция отображения страницы настроек
     */
    function build_settings_page()
    { ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <div id="icon-themes" class="icon32"></div>
            <h2>Настройка Diductio</h2>
            <?php settings_errors(); ?>
            <?php
            if (isset($_GET['tab'])) {
                $active_tab = $_GET['tab'];
            }
            ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=diductio-options&tab=display_options"
                   class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Основные
                    настройки</a>
            </h2>
            <form method="post" action="options.php">
                <?php
                switch ($active_tab) {
                    case "display_options":
                        settings_fields('diductio_main_settings');
                        $options = get_option('d_main_settings');
                        Diductio::gi()->loadView('admin.settings', $options);
                        submit_button();
                        break;
                }
                ?>
            </form>

        </div><!-- /.wrap -->
    <?php }

    /**
     *  Diductio init setting field
     */
    public function diductio_settings_init()
    {
        register_setting('diductio_main_settings', 'd_main_settings');
    }
}