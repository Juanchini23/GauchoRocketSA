<?php

class TourModel
{

    private $dataBase;

    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getTours($dia, $origen)
    {

        //proteccion para el SQL  :-)
        $diaSeguro = htmlentities($dia, ENT_QUOTES, 'utf-8');
        $origenSeguro = htmlentities($origen, ENT_QUOTES, 'utf-8');

        if ($dia && $origen) {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                    FROM planificacion p
                        JOIN lugar l ON p.idOrigen = l.id
                        JOIN modelo m ON p.idModelo = m.id
                        JOIN nave n ON m.idNave = n.id
                        JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                    WHERE l.descripcion = '{$origenSeguro}' AND p.dia = '{$diaSeguro}' AND tv.descripcion = 'Tour'");

        }
        if ($dia == null && $origen) {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                    FROM planificacion p
                        JOIN lugar l ON p.idOrigen = l.id
                        JOIN modelo m ON p.idModelo = m.id
                        JOIN nave n ON m.idNave = n.id
                        JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                    WHERE (l.descripcion = '{$origenSeguro}' OR p.dia = '{$diaSeguro}') AND tv.descripcion = 'Tour'");
        }
        if ($dia && $origen == null) {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                    FROM planificacion p
                        JOIN lugar l ON p.idOrigen = l.id
                        JOIN modelo m ON p.idModelo = m.id
                        JOIN nave n ON m.idNave = n.id
                        JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                    WHERE (l.descripcion = '{$origenSeguro}' OR p.dia = '{$diaSeguro}') AND tv.descripcion = 'Tour'");
        }

    }

    public function getTodosLosTours(){

        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                                        FROM planificacion p
                                            JOIN lugar l ON p.idOrigen = l.id
                                            JOIN modelo m ON p.idModelo = m.id
                                            JOIN nave n ON m.idNave = n.id
                                            JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                                        WHERE tv.descripcion = 'Tour'");

    }




}