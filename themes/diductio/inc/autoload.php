<?php
//Deprecated autoloading method
spl_autoload_register(function ($class_name) {
    if ($class_name !== 'Diductio' && $class_name[0] == 'd') {
        $class_name = substr($class_name, 1);
    }
    $file_name = strtolower($class_name) . '.class.php';
    $file = get_template_directory() . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $file_name;
    if (file_exists($file)) {
        require_once($file);
    }
});

spl_autoload_register(
    function ($class) {
        
        // Don't interfere with other autoloaders
        if (0 !== strpos($class, 'Did_')) {
            return;
        }
        
        $path = get_template_directory() . '/' . 'classes' . '/' . str_replace('Did_', '', $class) . '.class' . '.php';
        if (!file_exists($path)) {
            return;
        }
        
        require $path;
    }
);