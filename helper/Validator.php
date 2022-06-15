<?php

class Validator
{

    public static function validarSesion()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
            return $data;
        }
    }

}