<?php

class LoginController
{

    private $loginModel;
    private $printer;

    public function __construct($loginModel, $printer)
    {
        $this->loginModel = $loginModel;
        $this->printer = $printer;
    }

    public function execute()
    {
        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];
        $respuesta["loggeado"] = $this->loginModel->isUser($usuario, $clave);
        $respuesta["nombre"] = $usuario;

        $this->printer->generateView('homeView.html', $respuesta);
    }

}