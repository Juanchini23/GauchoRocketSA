<?php

class LoginModel
{
    private $database;
    private $nombreUsuario;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function iniciarSesion($usuario, $clave)
    {
        return $this->database->iniciarSesion($usuario, $clave);
    }

    public function isUser($user, $clave)
    {
        $usuarios = $this->iniciarSesion($user, $clave);
        foreach ($usuarios as $usuario) {
            if ($usuario["nombre"] == $user && $usuario["clave"] == $clave) {
                if ($usuario["idRol"] == 1) {
                    $_SESSION["AdminIn"] = 1;
                    $this->setNombreUsuario($usuario["nombre"]);
                    return "Admin";
                }
                if ($usuario["idRol"] == 2) {
                    $_SESSION["ClienIn"] = 2;
                    $this->setNombreUsuario($usuario["nombre"]);
                    return "Clien";
                }
            }
        }
    }

    public function setNombreUsuario(&$nombreUsuario)
    {
        $this->database->actualizarNombre($nombreUsuario);
    }


}