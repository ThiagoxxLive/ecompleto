<?php

namespace App\Models;

use App\Controllers\Controller;

class ProcessTransaction extends Controller
{

    private $Id;
    private $Data;
    private $Result;

    function getResult() {
        return $this->Result;
    }

    public function Process($Id) {

        $this->Id = (int) $Id;
        parent::isProtected();

        $Select = new \App\Models\Utils\Read();
        $Select->fullRead("SELECT a.*,
        c.nome customer_name,
        c.cpf_cnpj customer_document,
        c.email customer_mail,
        c.tipo_pessoa customer_type,
        c.data_nasc birthday 
        
        FROM pedidos_pagamentos a
        INNER JOIN pedidos b ON b.id=a.id_pedido
        INNER JOIN clientes c ON c.id=b.id_cliente
        
        WHERE a.id_pedido =:id_pedido LIMIT :limit", "id_pedido={$this->Id}&limit=1");

        if ($Select->getResult()) {

            $this->Data = $Select->getResult();            
            $this->MakeJson($this->Data);
        }
    }

    private function MakeJson(array $Data) {

        foreach ($Data as $row) {

            extract($row);

            switch ($customer_type) {
                case 'F':
                    $customer_type = "cpf";
                    break;
                case 'P';
                    $customer_type = "cnpj";
                    break;
            }

            $PostData = [
                'external_order_id' => $this->Id,
                'amount' => 21.40,
                'card_number' => $num_cartao,
                'card_cvv' => (string) $codigo_verificacao,
                'card_expiration_date' => $this->FormatExpirationDate($vencimento),
                'card_holder_name' => $nome_portador,
                'customer' => [
                    'external_id' => '5789',
                    'name' => $customer_name,
                    'type' => 'individual',
                    'email' => $customer_mail,
                    'documents' => [
                        'type' => $customer_type,
                        'number' => $customer_document
                    ],

                    'birthday' => $birthday
                ]
            ];            

            $PostData = json_encode($PostData);
            $this->MakePostRequest(API_ENDPOINT, $PostData);
        }
    }

    private function FormatExpirationDate($ExpirationDate) {

        $ExpirationDate = explode("-", $ExpirationDate);

        $Year = substr($ExpirationDate[0], 2, 4);
        $Month = $ExpirationDate[1];

        $ExpirationDate = $Month . $Year;
        return $ExpirationDate;
    }

    private function MakePostRequest($Url, $Data) {

        $Headers = array(
            'Content-Type:application/json',
            'Authorization: ' . API_SECRET,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $response = json_decode($response);        

        $UpdateData = ['retorno_intermediador' => $response->Message, 'data_processamento' => date("Y-m-d H:i:s")];
        $TransactionData = ['data' => date("Y-m-d H:i:s"), 'id_situacao' => $response->Transaction_code];

        switch($response->Transaction_code) {

            case "00":
                $this->UpdateStatus($UpdateData, $TransactionData, true);
                break;

            case "03":
                $this->UpdateStatus($UpdateData, $TransactionData, false);
                break;
        }
    }

    private function UpdateStatus(array $OrderData, $OrderStatus, $Status) {

            $UpdateTransactionStatus = new \App\Models\UpdateTransactionStatus();
            $UpdateTransactionStatus->Update($this->Id, $OrderData);
            
            if($UpdateTransactionStatus->getResult()) {
                
                $UpdateOrderStatus = new \App\Models\UpdateOrderStatus();
                $UpdateOrderStatus->Update($this->Id, $OrderStatus);

                if(!$UpdateOrderStatus->getResult()) {
                    $this->Result = $Status;
                }
                
            } else {
                $this->Result = $Status;
            }
    }
}
