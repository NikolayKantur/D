<?php
/*
Plugin Name: Diductio
Description: Добавляет функционал проекта Diductio на сайт
Text Domain: diductio
Author: Danil T.
Version: 1.0.0
License: GPL 3.0
*/

define('DIDUCTIO_PLUGIN_PATH', dirname(__FILE__));

spl_autoload_register(
    function ($class) {
        // Don't interfere with other autoloaders
        if (0 !== strpos($class, 'Did_') && $class !== 'Diductio') {
            return;
        }

        $path = __DIR__ . '/' . 'classes' . '/' . str_replace('Did_', '', $class) . '.class' . '.php';
        if (!file_exists($path)) {
            return;
        }
        
        require $path;
    }
);