<?php

namespace App\Models\Utils;

use App\Models\Utils\Connection;
use Exception;

if(!defined('URL')) {

    header("Location: /");
    exit();
}

class Update extends Connection
{

    private $Table;
    private $Data;
    private $Query;
    private $Conn;
    private $Result;
    private $Terms;
    private $Values;

    function getResult()
    {
        return $this->Result;
    }

    public function exeUpdate($Table, array $Data, $Terms = null, $ParseString = null)
    {
        $this->Table = (string) $Table;
        $this->Data = $Data;
        $this->Terms = (string) $Terms;

        parse_str($ParseString, $this->Values);
        $this->getInstruction();
        $this->executeInstruction();
    }

    private function getInstruction()
    {
        foreach ($this->Data as $key => $Value) {

            $Values[] = $key . '= :' . $key;

        }

        $Values = implode(', ', $Values);
        $this->Query = "UPDATE {$this->Table} SET {$Values} {$this->Terms}";
    }

    private function executeInstruction()
    {
        $this->connection();

        try {

            $this->Query->execute(array_merge($this->Data, $this->Values));
            $this->Result = true;

        } catch (Exception $e) {

            $this->Result = null;
        }
    }

    private function connection()
    {
        $this->Conn = parent::getConn();
        $this->Query = $this->Conn->prepare($this->Query);
    }

}
