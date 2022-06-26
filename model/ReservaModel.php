<?php

class ReservaModel
{

    private $dataBase;
    private $cero = 0;
    private $circuitoUnoBA = array(["Tierra"=> 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra"=> 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra"=> 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra"=> 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);


    /*let circuitoUnoBA = {"Tierra": 0, "EEI": 4, "HotelOrbital": 8, "Luna": 16, "Marte": 26};
     *   $circuitoUnoAA = {"Tierra": 0, "EEI": 3, "HotelOrbital": 6, "Luna": 9, "Marte": 22};
      $circuitoDosBA = {"Tierra": 0, "Luna": 14, "Marte": 26, "Ganimedes": 48, "Europa": 50, "Io": 51, "Encedalo": 70, "Titan": 77};
      $circuitoDosAA = {"Tierra": 0, "EEI": 3, "Luna": 10, "Marte": 22, "Ganimedes": 32, "Europa": 33, "Io": 35, "Encedalo": 50, "Titan": 52};*/


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
        $origenID = '';
        $destinoID = '';
        switch ($origen) {
            case 'BA':
                $origenID = 1;
                break;
            case 'AK':
                $origenID = 2;
                break;
            case 'EEI':
                $origenID = 11;
                break;
            case 'HotelOrbital':
                $origenID = 10;
                break;
            case 'Luna':
                $origenID = 9;
                break;
            case 'Marte':
                $origenID = 8;
                break;
            case 'Ganimedes':
                $origenID = 7;
                break;
            case 'Europa':
                $origenID = 6;
                break;
            case 'Io':
                $origenID = 5;
                break;
            case 'Encedalo':
                $origenID = 4;
                break;
            case 'Titan':
                $origenID = 3;
                break;
            default:
                $origenID = 0;
        }

        switch ($destino) {
            case 'BA':
                $destinoID = 1;
                break;
            case 'AK':
                $destinoID = 2;
                break;
            case 'EEI':
                $destinoID = 11;
                break;
            case 'HotelOrbital':
                $destinoID = 10;
                break;
            case 'Luna':
                $destinoID = 9;
                break;
            case 'Marte':
                $destinoID = 8;
                break;
            case 'Ganimedes':
                $destinoID = 7;
                break;
            case 'Europa':
                $destinoID = 6;
                break;
            case 'Io':
                $destinoID = 5;
                break;
            case 'Encedalo':
                $destinoID = 4;
                break;
            case 'Titan':
                $destinoID = 3;
                break;
            default:
                $destinoID = 0;
        }

        /*// 1- ver si existe una reserva con el id de la planificacion
        $existe = $this->dataBase->query("SELECT *
                                            FROM reserva r JOIN planificacion p ON r.idPlanificacion = p.id
                                            WHERE p.id = '$idPlanificacion'
                                            AND r.origen = '$origen'
                                            AND r.destino = '$destino';");
        $idReserva = $existe['id'];*/
        /*$contadorErrores = 0;*/

        //////////////////// Logica para reservar  ////////////////////////
        // para obtener la key del array o el array de keys
        $keyCircuitoUnoBA = array_keys($this->circuitoUnoBA[0]);

        $entro = false;
        if($origen=='AK' || $origen=='BA'){
            $keyCircuitoUnoBA[0]='Tierra';
        }
        $origen = 'EEI';
        $destino = 'Luna';
        $i=-1;
        do{
            $i++;
            if ($keyCircuitoUnoBA[$i] == $origen) {
                $entro = true;
            }
            if ($entro == true) {
                echo($keyCircuitoUnoBA[$i]);
                echo '+';
                echo($keyCircuitoUnoBA[$i+1]);
                echo '<br>';
            }
        }while($keyCircuitoUnoBA[$i+1]!=$destino);
        /////////////////////////////////////////////////////////////////////

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
        /*if ($sumaAsientos >= $cantidadM) {
            $_SESSION['errorNoHayAciento']=1;
            header("location:/");
            exit();
        } else{
            if ($butaca == 'turista') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$origenID','$destinoID');");
            } elseif ($butaca == 'ejecutiva') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$origenID','$destinoID');");
            } elseif ($butaca == 'primera') {
                $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$origenID','$destinoID');");
            }
        }*/


        //        Calcularlo
        //$diaLlegada = $_POST["diaLlegada"] ?? "";
        //$horaLlegada = $_POST["horaLlegada"] ?? "";

    }
}