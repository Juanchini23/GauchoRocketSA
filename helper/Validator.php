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

    public static function generarCodigo($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
}