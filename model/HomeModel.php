<?php

class HomeModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function busquedaVuelos($origen, $destino, $salida, $vuelta)
    {
        return $this->database->query("SELECT * FROM vuelo WHERE origen =  '$origen' AND destino = '$destino';");
//        AND salida = '$salida' AND vuelta = '$vuelta'
    }

    public function solicitarNombreUsuario()
    {
        $usurios = $this->database->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio) {
            return $usurio["nombre"];
        }
    }


}

