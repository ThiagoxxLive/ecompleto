<?php 

namespace App\Controllers;

class ProcessTransaction extends Controller {

    private $Id;


    public function index($Id) {

        $this->Id = $Id;
        parent::isProtected();

        $SelectProcess = new \App\Models\ProcessTransaction();
        $SelectProcess->Process($this->Id);

        if(!$SelectProcess->getResult()) {

            $_SESSION['error_message'] = "<div class='alert card red lighten-4 red-text text-darken-4'><div class='card-content'><p>Ocorreu um erro ao processar o pagamento.</p></div></div>";
            $Target = URL . '/home';
            header("Location: $Target");

        } else {
            $_SESSION['error_message'] = "<div class='alert card green lighten-4 white-text text-darken-4'><div class='card-content'><p>Pagamento autorizado com sucesso.</p></div></div>";
            $Target = URL . '/home';
            header("Location: $Target");
        }

    }

}