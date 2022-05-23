<?php

class HomeController {
    private $printer;

    public function __construct($printer, $homeModel) {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute() {
        $this->printer->generateView('homeView.html');
    }

    public function login(){
        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];


    }
}