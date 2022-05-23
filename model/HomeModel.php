<?php

class HomeModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getVuelos()
    {

    }

    private function login($usuario, $clave)
    {
        return $this->database->query("SELECT nombre, clave FROM usuario WHERE nombre = '$usuario' AND clave = '$clave'");
    }

    public function isUser($user, $clave)
    {
        $usuarios = $this->login($user, $clave);
        foreach ($usuarios as $usuario) {
            if ($usuario["nombre"] == $user && $usuario["clave"] == $clave) {
                $_SESSION["login"] = 1;
                return true;
            }
        }
    }

}