<?php

namespace App\Models;

use App\Controllers\Controller;

class UpdateTransactionStatus extends Controller {

    private $Id;
    private $Data;
    private $Result;

    function getResult() {
        return $this->Result;
    }

    public function Update($Id, array $Data) {

        $this->Id = (int) $Id;
        $this->Data = $Data;

        $Update = new \App\Models\Utils\Update();
        $Update->exeUpdate("pedidos_pagamentos", $this->Data, "WHERE id_pedido =:id_pedido", "id_pedido={$this->Id}");

        if($Update->getResult()) {
            $this->Result = true;
        }
    }
}