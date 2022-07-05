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

    public function getTOcupacionPorTipoViaje($tipoEquipo, $nombre)
    {
        return $this->dataBase->query("SELECT COUNT(m.idTipoEquipo) AS '$nombre'
FROM reserva r
JOIN planificacion p ON r.idPlanificacion = p.id
JOIN modelo m on p.idModelo = m.id
JOIN tipoEquipo tE on m.idTipoEquipo = tE.id
WHERE tE.descripcion = '$tipoEquipo';");
    }

    public function getCabinaTurita($cabina)
    {
        return $this->dataBase->query("SELECT SUM($cabina) AS '$cabina'
FROM reserva;");
    }

    public function getMesActual()
    {
        switch (date("m")) {
            case 01:
                return 'Enero';
            case 2:
                return 'Febrero';
            case 3:
                return 'Marzo';
            case 4:
                return 'Abril';
            case 5:
                return 'Mayo';
            case 6:
                return 'Junio';
            case 7:
                return 'Julio';
            case 8:
                return 'Agosto';
            case 9:
                return 'Septiempre';
            case 10:
                return 'Octubre';
            case 11:
                return 'Noviembre';
            case 12:
                return 'Diciembre';

        }
    }

    public function getFacturacionMensual()
    {
        $mes = date("m");
        return $this->dataBase->query("SELECT SUM(precio) AS 'facturacionMensual'
                                        FROM reservaCompleta rC
                                        WHERE rC.fecha like '%-$mes-%';");
    }

}