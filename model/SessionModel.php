<?php

class SessionModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function isUser($user, $clave)
    {
        $usuarios = $this->login($user, $clave);
        foreach ($usuarios as $usuario) {
            if ($usuario["nombre"] == $user && $usuario["clave"] == $clave) {
                return true;
            }
        }
    }

}