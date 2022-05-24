<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute($respuesta = [])
    {
        $this->printer->generateView('homeView.html', $respuesta);
    }

    public function registrarse()
    {
        $this->printer->generateView('formRegistro.html');
    }


    public function login()
    {

        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];
        $respuesta["loggeado"] = $this->homeModel->isUser($usuario, $clave);
        $this->execute($respuesta);
    }

    public function logout()
    {
        session_encode();
        session_destroy();
        $this->printer->generateView('homeView.html');
    }
}