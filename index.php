<?php

    require './core/Config.php';
    require './vendor/autoload.php';

    use Core\Router as Home;
    $Url = new Home();
    $Url->Load();

?>