<?php

class OrbitalModel
{

    private $dataBase;

    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }



    public function getOrbitales($dia, $origen,$codigoViajero)
    {
        $diaLetra = "";

        switch ($dia) {
            case "Monday":
                $diaLetra = "Lunes";
                break;

            case "Tuesday":
                $diaLetra = "Martes";
                break;

            case "Wednesday":
                $diaLetra = "Miercoles";
                break;

            case "Thursday":
                $diaLetra = "Jueves";
                break;

            case "Friday":
                $diaLetra = "Viernes";
                break;

            case "Saturday":
                $diaLetra = "Sabado";
                break;

            case "Sunday":
                $diaLetra = "Domingo";
                break;

            default:
                break;
        }

        if(strlen($diaLetra) == null || strlen($origen) == null){
        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                                        FROM planificacion p
                                            JOIN lugar l ON p.idOrigen = l.id
                                            JOIN modelo m ON p.idModelo = m.id
                                            JOIN nave n ON m.idNave = n.id
                                            JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                                            JOIN tipoCliente tc ON m.tipoCliente = tc.id
                                        WHERE (l.descripcion = '{$origen}' 
                                        OR p.dia = '{$diaLetra}')
                                        AND (tv.descripcion = 'Orbitales')
                                        AND tc.descripcion like '%$codigoViajero%'");

    } else{
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                                        FROM planificacion p
                                            JOIN lugar l ON p.idOrigen = l.id
                                            JOIN modelo m ON p.idModelo = m.id
                                            JOIN nave n ON m.idNave = n.id
                                            JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                                            JOIN tipoCliente tc ON m.tipoCliente = tc.id
                                        WHERE (l.descripcion = '{$origen}' 
                                        AND p.dia = '{$diaLetra}')
                                        AND tv.descripcion = 'Orbitales'
                                        AND tc.descripcion like '%$codigoViajero%'");
        }
    }



}