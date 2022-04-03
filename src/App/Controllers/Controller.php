<?php

namespace App\Controllers;

class Controller {

    public function isProtected() {
        if(!defined('URL')) {
            header("Location: /");
            exit();
        }
    }
    
}