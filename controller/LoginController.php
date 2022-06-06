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
    {   // poner lo de isset a lo que viene por post porque sino dice undefinied cuando queremos
        // romper pegando el link en ventana de incognito
        $usuario = $_POST["usuario"] ?? "";
        $clave = md5($_POST["clave"] ?? "");
        $respuesta["loggeado"] = $this->loginModel->isUser($usuario, $clave);
        //$respuesta["nombre2"] = $_SESSION["usuario"];

        header("location: /");
		exit();

    }

}