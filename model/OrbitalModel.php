<?php

class OrbitalModel
{

    private $dataBase;

    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getOrbitales($dia, $origen)
    {

        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE o.descripcion = '$origen'
OR p.dia = '$dia'
AND tv.descripcion = 'Orbitales'");


    }
}