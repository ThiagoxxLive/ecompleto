<?php

namespace Core;

class Router {

    private $Url;
    private $FullUrl;
    private $UrlController;
    private $UrlParam;
    private static $Format;

    public function __construct(){

        if(!empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT))) {

            $this->Url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
            $this->ClearUrl();
            $this->FullUrl = explode("/", $this->Url);

            if(isset($this->FullUrl[0])) {
                $this->UrlController = $this->SlugController($this->FullUrl[0]);

            } else {
                $this->UrlController = CONTROLLER;
            }

            if(isset($this->FullUrl[1])) {
                $this->UrlParam = $this->FullUrl[1];

            } else {
                $this->UrlParam = null;
            }


            //echo $this->Url . "<br>";
            //echo "Controller: {$this->UrlController}";

        } else {

            $this->UrlController = CONTROLLER;
            $this->UrlParam = null;

        }

    }

    private function ClearUrl() {

        $this->Url = strip_tags($this->Url);
        $this->Url = trim($this->Url);
        $this->Url = rtrim($this->Url, "/");

        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:.,\\\'<>°ºª ';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr--------------------------------';
        $this->Url = strtr(utf8_decode($this->Url), utf8_decode(self::$Format['a']), self::$Format['b']);
    }

    private function SlugController($Controller) {

        $Controller = strtolower($Controller);
        $Controller = explode("-", $Controller);
        $Controller = implode(" ", $Controller);
        $Controller = ucwords($Controller);
        $Controller = str_replace(" ", "", $Controller);
        return $Controller;

    }

    public function Load() {

        if(file_exists('src/App/Controllers/' . $this->UrlController . '.php')) {

            $Class = "\\App\\Controllers\\" . $this->UrlController;
            $Load = new $Class;

            if($this->UrlParam !== null) {

                $Load->index($this->UrlParam);

            } else {

                $Load->index();

            }
            
        } else {

            echo 404;
        }
    }

}