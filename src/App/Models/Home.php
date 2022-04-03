<?php 

namespace App\Models;

use App\Controllers\Controller;

class Home extends Controller {

    private $Data;
    private $Result;

    function getResult() {

        return $this->Result;

    }

    public function SelectAll() {       

        parent::isProtected();
        
        $Select = new \App\Models\Utils\Read();
        $Select->fullRead("SELECT a.*,
        b.nome customer_name,
        c.descricao order_description,
        d.id_formapagto id_forma_pgto,
        e.descricao forma_pgto

        FROM pedidos a
        INNER JOIN clientes b ON b.id=a.id_cliente
        INNER JOIN pedido_situacao c ON c.id=a.id_situacao
        INNER JOIN pedidos_pagamentos d ON d.id_pedido = a.id
        INNER JOIN formas_pagamento e ON e.id=d.id_formapagto

        
        ");

        if($Select->getResult()) {

            $this->Result = true;
            $this->Data = $Select->getResult();

            return $this->Data;

        } 
    }
}