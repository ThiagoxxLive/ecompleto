<?php

namespace App\Controllers;

class Orders extends Controller {

    public function index() {

        parent::isProtected();
        echo "Página Orders";
    }
    
}