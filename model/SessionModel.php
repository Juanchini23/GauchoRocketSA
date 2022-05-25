<?php

class SessionModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function isUser($usuario, $clave)
    {
        $usuarios = $this->database->query("SELECT nombre, clave, idRol FROM usuario WHERE nombre = '$usuario' AND clave = '$clave'");
        return sizeof($usuarios) == 1;
    }

}