<?php

class ReservaModel
{

    private $dataBase;
    private $cero = 0;
    private $circuitoUnoBA = array(["Tierra" => 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra" => 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra" => 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra" => 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);

    public function __construct($getDataBase)
    {
        $this->dataBase = $getDataBase;
    }

    public function guardarViajeFecha($idUser, $id, $fechaViaje)
    {
        $date = date_create($fechaViaje);
        $date = $date->format('Y-m-d');
        $this->dataBase->guardarVueloFecha($idUser, $id, $date);
    }

    public function getPlanificacion($id)
    {
        return $this->dataBase->getPlani($id);
    }

    public function getUsuario($idUser)
    {
        $this->dataBase->getUsu($idUser);
    }

    public function getDatosModelo($id)
    {
        return $this->dataBase->getDatosModelo($id);
    }

    public function generarReserva($origen, $destino, $diaSalida, $horaSalida, $butaca, $cantidadAsientos, $metodoPago, $idUser, $idPlanificacion, $fecha)
    {

        $planificacion = $this->dataBase->getPlani($idPlanificacion);


        /*// 1- ver si existe una reserva con el id de la planificacion
        $existe = $this->dataBase->query("SELECT *
                                            FROM reserva r JOIN planificacion p ON r.idPlanificacion = p.id
                                            WHERE p.id = '$idPlanificacion'
                                            AND r.origen = '$origen'
                                            AND r.destino = '$destino';");
        $idReserva = $existe['id'];*/
        /*$contadorErrores = 0;*/

        // Para saber la cantidad maxima de butacas que tiene el modelo de nave.
        $cantidadMaximaButacaSeleccionada = $this->dataBase->query("SELECT $butaca as 'butaca' FROM planificacion p JOIN modelo m ON p.idModelo = m.id
                                                                    WHERE p.id = '$idPlanificacion';");

        // Para saber la cantidad actual de butacas que tiene la reserva.
        $cantidadActualButacaReservadas = $this->dataBase->query("SELECT SUM($butaca) as 'cantidad' FROM reserva r JOIN planificacion p on r.idPlanificacion = p.id
                                                                    WHERE p.id = '$idPlanificacion'
                                                                    AND r.fecha = '$fecha';");

        //////////////////// Logica para reservar  ////////////////////////
        $cantidadM = $cantidadMaximaButacaSeleccionada[0]['butaca'];

        $cantidadA = $cantidadActualButacaReservadas[0]['cantidad'];

        $sumaAsientos = $cantidadA + $cantidadAsientos;
        // para obtener la key del array o el array de keys
        $keyCircuitoUnoBA = array_keys($this->circuitoUnoBA[0]);

        $entro = false;
        if ($origen == 'AK' || $origen == 'BA') {
            $origen = 'Tierra';
        }
        $i = -1;
        do {
            $i++;
            if ($keyCircuitoUnoBA[$i] == $origen) {
                $entro = true;
            }
            if ($entro == true) {
                // verificar la cantidad de asientos y la clase
                if ($sumaAsientos >= $cantidadM) {
                    $_SESSION['errorNoHayAciento'] = 1;
                    header("location:/");
                    exit();
                } else {
                    $o = $this->getIdLugar($keyCircuitoUnoBA[$i]);
                    $d = $this->getIdLugar($keyCircuitoUnoBA[$i + 1]);
                    if ($butaca == 'turista') {
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                    } elseif ($butaca == 'ejecutivo') {
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                    } elseif ($butaca == 'primera') {
                        var_dump($o);
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                    }
                }
            }
        } while ($keyCircuitoUnoBA[$i + 1] != $destino);
        /////////////////////////////////////////////////////////////////////

        //        Calcularlo
        //$diaLlegada = $_POST["diaLlegada"] ?? "";
        //$horaLlegada = $_POST["horaLlegada"] ?? "";

    }

    private function getIdLugar($lugar)
    {
        switch ($lugar) {
            case 'Tierra':
                return 1;
            case 'EEI':
                return 11;
            case 'HotelOrbital':
                return 10;
            case 'Luna':
                return 9;
            case 'Marte':
                return 8;
            case 'Ganimedes':
                return 7;
            case 'Europa':
                return 6;
            case 'Io':
                return 5;
            case 'Encedalo':
                return 4;
            case 'Titan':
                return 3;
            default:
                return 0;
        }
    }
}