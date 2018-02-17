<?php

namespace DiductioSqlPackages\App\AdminInterface\Pages\Controllers;

class Manage 
{
    public function __construct($Model) 
    {
        $this->Model = $Model;
    }

    public function execute() 
    {
        $this->View->display();
    }

    public function setView($View) {
        $this->View = $View;
    }

    public function registerPage() {
        $params = $this->Model->getPageParams();

        add_submenu_page(
            $params['parent'],
            $params['page_title'], 
            $params['menu_title'], 
            $params['capability'], 
            $params['slug'], 
            [$this, 'execute']
        );
    }
}
