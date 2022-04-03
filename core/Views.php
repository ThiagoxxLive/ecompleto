<?php 

namespace Core;

class Views {

    private $ViewName;
    private $Data;

    public function __construct($ViewName, array $Data = null)  {

        $this->ViewName = (string) $ViewName;
        $this->Data = $Data;

    }


    public function Render() {

        if(file_exists('src/' . $this->ViewName . '.php')) {

            include 'src/App/Views/includes/header.php';
            include 'src/App/Views/includes/head.php';
            include 'src/' . $this->ViewName . '.php';
            include 'src/App/Views/includes/footer.php';

        } else {

            echo "Página não encontrada";
        }

        
    }


}