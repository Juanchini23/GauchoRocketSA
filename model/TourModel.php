<?php

class TourModel {

    private $dataBase;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getTours($dia, $origen)
    {
        if (strlen($dia) == null || strlen($origen) == null) {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE o.descripcion = '$origen'
OR p.dia = '$dia'
AND tv.descripcion = 'Tour'");
        } else {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE o.descripcion = '$origen'
AND p.dia = '$dia'
AND tv.descripcion = 'Tour'");
        }

    }

}