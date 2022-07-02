<?php

class AdminModel
{
    private $dataBase;

    public function __construct($dataBase)
    {

        $this->dataBase = $dataBase;
    }

    public function getTOcupacionPorviaje($id, $nombre)
    {
        return $this->dataBase->query("SELECT COUNT(p.idTipoVuelo) AS '$nombre'
FROM reserva r
JOIN planificacion p on r.idPlanificacion = p.id
WHERE p.idTipoVuelo = '$id';");
    }
}