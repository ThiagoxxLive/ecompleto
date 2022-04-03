<?php

namespace App\Controllers;

class Home extends Controller {

    private $Data;

    public function index() {

        parent::isProtected();

        $Home = new \App\Models\Home();
        $this->Data = $Home->SelectAll();        
        
        $View = new \Core\Views("App/Views/home/home", $this->Data);
        $View->Render();
    }
    
}