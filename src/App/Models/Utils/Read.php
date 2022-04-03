<?php 

namespace App\Models\Utils;
use App\Models\Utils\Connection;
use Exception;
use PDO;

if(!defined('URL')) {

    header("Location: /");
    exit();
}

class Read extends Connection {

    private $Select;
    private $Values;
    private $Result;
    private $Query;
    private $Conn;

    function getResult() {
        
        return $this->Result;
    }

    public function exeRead($Table, $Terms = null, $ParseString = null) {

        if(!empty($ParseString)) {

            parse_str($ParseString, $this->Values);

        }

        $this->Select = "SELECT * FROM {$Table} {$Terms}";        

        $this->execute();
    }

    public function fullRead($Query, $ParseString = null) {

        $this->Select = (string) $Query;

        if(!empty($ParseString)) {

            parse_str($ParseString, $this->Values);
            
        }

        $this->execute();

    }

    private function execute() {

        $this->connection();

        try {

            $this->getInstruction();
            $this->Query->execute();
            $this->Result = $this->Query->fetchAll();            

        }catch(Exception $e) {

            $this->Result = null;
            echo "Erro ao executar instrução: {$e}";
        }

    }   

    private function connection() {

       $this->Conn = parent::GetConn();
       $this->Query = $this->Conn->prepare($this->Select);
       $this->Query->setFetchMode(PDO::FETCH_ASSOC);

    }

    private function getInstruction() {

        if($this->Values) {

            foreach($this->Values as $Link => $Value) {

                if($Link == 'limit' || $Link == 'offset') {

                    $Value = (int) $Value;
                    
                }

                $this->Query->bindValue(":{$Link}", $Value, (is_int($Value) ? PDO::PARAM_INT : PDO::PARAM_STR));
                
            }
        }
    }

}