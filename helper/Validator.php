<?php

class Validator
{

    public static function validarSesion()
    {
        if (isset($_SESSION["ClienIn"]) || isset($_SESSION["AdminIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
            if (isset($_SESSION["AdminIn"])) {
                $data["AdminIn"] = 2;
            }
            return $data;

        }
    }

}