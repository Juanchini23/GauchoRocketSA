<?php

class SessionController
{

    private $sessionModel;

    public function __construct($sessionModel)
    {
        $this->sessionModel = $sessionModel;
    }

    public function execute(){

        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];
        $_SESSION["loggeado"] = $this->sessionModel->isUser($usuario, $clave);

    }


}