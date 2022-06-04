<?php

class HomeModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function busquedaVuelos($origen)
    {
        return $this->database->query("SELECT p.dia, p.horaPartida, o.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE o.descripcion = '$origen';");
    }

    public function solicitarNombreUsuario()
    {
        $usurios = $this->database->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio) {
            return $usurio["nombre"];
        }
    }


}

