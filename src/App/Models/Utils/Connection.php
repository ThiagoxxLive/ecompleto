<?php

namespace App\Models\Utils;
use App\Controllers\Controller;
use Exception;
use PDO;

class Connection extends Controller {

    public static $Host = HOST;
    public static $User = USER;
    public static $Pass = PASS;
    public static $DbName = DBNAME;
    private static $Connect = null;

    private static function Connect() {

        try {

            if(self::$Connect == null) {

                self::$Connect = new PDO("pgsql:host=localhost;dbname=db_exercicio;user=postgres;password=root");                
            }


        }catch(Exception $e) {
            die($e->getMessage());
        }

        return self::$Connect;

    }


    public function GetConn() {

        return self::Connect();        

    }

    

}