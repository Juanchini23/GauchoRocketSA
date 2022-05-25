<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute(){

        if(isset($_SESSION["ClienIn"])){
            $respuesta["loggeado"] = 1;
        }
        $this->printer->generateView('homeView.html', $respuesta);
    }


    public function logout(){
        session_encode();
        session_destroy();
        $this->printer->generateView('homeView.html');
    }


}