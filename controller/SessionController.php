<?php

class SessionController
{

    private $sessionModel;

    public function __construct($sessionModel)
    {
        $this->sessionModel = $sessionModel;
    }

    public function execute()
    {

        $usuario = $this->validateSiPostParamExiste("usuario");
        $password = $this->validateSiPostParamExiste("clave");

        $loginValido = $this->sessionModel->login($usuario, $password);

        $_SESSION["login"] = $loginValido;

        header("location: /");
        exit();
    }

    private function validateSiPostParamExiste($valor)
    {
        return $_POST[$valor] ?? "";
    }
}