<?php

namespace App\Controllers;

class Customers extends Controller {

    public function index() {
        
        parent::isProtected();
        echo "Página Customers";
    }
    
}