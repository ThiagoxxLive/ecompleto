<?php

namespace App\Models;
use App\Controllers\Controller;

class UpdateOrderStatus extends Controller {

    private $Id;
    private $Data;
    private $Result;

    function getResult() {
        return $this->Result;
    }

    public function Update($Id, array $Data) {

        $this->Id = $Id;
        $this->Data = $Data;
        
        $Update = new \App\Models\Utils\Update();
        $Update->exeUpdate("pedidos", $this->Data, "WHERE id =:id", "id={$this->Id}");

        if(!$Update->getResult()) {
            echo "Ocorreu um erro ao atualizar a base de dados";
        }

    }
}