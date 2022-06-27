<?php

class TourModel
{

    private $dataBase;

    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getTours($dia)
    {


        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                    FROM planificacion p
                        JOIN lugar l ON p.idOrigen = l.id
                        JOIN modelo m ON p.idModelo = m.id
                        JOIN nave n ON m.idNave = n.id
                        JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                    WHERE p.dia = '{$dia}' AND tv.descripcion = 'Tour'");


    }

    public function getTodosLosTours()
    {

        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo'
                                        FROM planificacion p
                                            JOIN lugar l ON p.idOrigen = l.id
                                            JOIN modelo m ON p.idModelo = m.id
                                            JOIN nave n ON m.idNave = n.id
                                            JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                                        WHERE tv.descripcion = 'Tour'");

    }


    public function getPlanificacion($id)
    {
        return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
                                        FROM planificacion p
                                                JOIN lugar l ON p.idOrigen = l.id
                                                JOIN modelo m ON p.idModelo = m.id
                                                JOIN nave n ON m.idNave = n.id
                                                JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
                                        WHERE p.id = '{$id}'");
    }


    public function getDatosModelo($id)
    {
        return $this->dataBase->query("SELECT n.modelo as 'nombreNave', m.turista as 'turista', m.ejecutivo as 'ejecutivo', m.primera as 'primera', te.descripcion as 'tipoEquipo', tc.descripcion as 'tipoCliente'
                                        FROM planificacion p
                                            JOIN modelo m ON p.idModelo = m.id
                                            JOIN nave n on m.idNave = n.id
                                            JOIN tipoEquipo tE on m.tipoEquipo = te.id
                                            JOIN tipoCliente tc on m.tipoCliente = tc.id
                                        WHERE p.id = '{$id}'");
    }


    public function reservaTour($origen, $butaca, $cantidadAsientos, $idUser, $idPlanificacion, $fecha)
    {
        $origenID = '';

        if ($origen == 'BA') {
            $origenID = 1;
        }

        // Para saber la cantidad maxima de butacas que tiene el modelo de nave.
        $cantidadMaximaButacaSeleccionada = $this->dataBase->query("SELECT $butaca as 'butaca' FROM planificacion p JOIN modelo m ON p.idModelo = m.id
                                                                    WHERE p.id = '$idPlanificacion';");

        // Para saber la cantidad actual de butacas que tiene la reserva.
        $cantidadActualButacaReservadas = $this->dataBase->query("SELECT SUM($butaca) as 'cantidad' FROM reserva r JOIN planificacion p on r.idPlanificacion = p.id
                                                                    WHERE p.id = '$idPlanificacion'
                                                                    AND r.fecha = '$fecha';");

        $cantidadM = $cantidadMaximaButacaSeleccionada[0]['butaca'];

        $cantidadA = $cantidadActualButacaReservadas[0]['cantidad'];

        $sumaAsientos = $cantidadA + $cantidadAsientos;

        // verificar la cantidad de asientos y la clase
        if ($sumaAsientos > $cantidadM) {
            $_SESSION['errorNoHayAciento'] = 1;
            header("location:/tour");
            exit();
        } else {
            if ($butaca == 'turista') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$origenID','$origenID');");
            } elseif ($butaca == 'ejecutiva') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$origenID','$origenID');");
            } elseif ($butaca == 'primera') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$origenID','$origenID');");
            }
        }


    }

    public function getCantidadAsientosReservados($id, $fechaViaje)
    {
        return $this->dataBase->query("SELECT SUM(turista) AS 'turista', SUM(ejecutivo) AS 'ejecutivo', SUM(primera) AS 'primera' 
                                FROM reserva r 
                               WHERE r.idPlanificacion = '$id' AND r.fecha = '$fechaViaje';");
    }


}