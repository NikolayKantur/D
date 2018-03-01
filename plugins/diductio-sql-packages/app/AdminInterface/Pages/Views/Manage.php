<?php

namespace DiductioSqlPackages\App\AdminInterface\Pages\Views;

use DiductioSqlPackages\App\Templates;

class Manage 
{
    private $template = 'manage';

    public function __construct($Controller, $Model) {
        //
    }

    public function display() 
    {
        $TemplatesRenderer = new Templates\Renderer;
        echo $TemplatesRenderer->render($this->template);
    }
}
