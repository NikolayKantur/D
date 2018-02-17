<?php

namespace DiductioSqlPackages\App;

use DiductioSqlPackages\App\AdminInterface\Pages;

class Main {
    public function init() 
    {
        $this->initAdminInterface();
    }

    private function initAdminInterface() 
    {
        $Model = new Pages\Models\Manage;

        $Controller = new Pages\Controllers\Manage($Model);
        $View = new Pages\Views\Manage($Controller, $Model);
        
        $Controller->setView($View);

        add_action('admin_menu', [$Controller, 'registerPage'], 10);
    }
}