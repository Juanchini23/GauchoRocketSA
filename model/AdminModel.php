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

    public function getTOcupacionPorTipoCiaje($tipoEquipo, $nombre)
    {
        return $this->dataBase->query("SELECT COUNT(m.tipoEquipo) AS '$nombre'
FROM reserva r
JOIN planificacion p ON r.idPlanificacion = p.id
JOIN modelo m on p.idModelo = m.id
JOIN tipoEquipo tE on m.tipoEquipo = tE.id
WHERE tE.descripcion = '$tipoEquipo';");
    }

    public function getCabinaTurita($cabina)
    {
        return $this->dataBase->query("SELECT SUM($cabina) AS '$cabina'
FROM reserva;");
    }

}