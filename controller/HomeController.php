<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute()
    {
        $this->printer->generateView('homeView.html');
    }

    public function registrarse()
    {
        $this->printer->generateView('formRegistro.html');
    }


    public function login(){

        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];
        $respuesta = $this->homeModel->isUser($usuario, $clave);
        if ($respuesta) {
            $this->execute();
        }
    }
}