<?php

class ReservaModel
{

    private $dataBase;
    private $cero = 0;
    private $circuitoUnoBA = array(["Tierra" => 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra" => 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra" => 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra" => 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);


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

    public function generarReserva($origen, $destino, $diaSalida, $horaSalida, $butaca, $cantidadAsientos, $metodoPago, $idUser, $idPlanificacion)
    {
        $planificacion = $this->dataBase->getPlani($idPlanificacion);

        // 1- ver si existe una reserva con el id de la planificacion
        $existe = $this->dataBase->query("select *
                                            from reserva r join planificacion p on r.idPlanificacion = p.id
                                            where p.id = '$idPlanificacion'
                                            and r.origen = '$origen'
                                            and r.destino = '$destino';");
        $idReserva = $existe['id'];
        $contadorErrores = 0;

        //normalizar en mysqldatabase
        $cantidadMaximaTurista = $this->dataBase->query($idPlanificacion);
        $cantidadMaximaEjecutivo = $this->dataBase->query($idPlanificacion);
        $cantidadMaximaPrimera = $this->dataBase->query($idPlanificacion);

        $cantidadActualTurista = $this->dataBase->query();
        $cantidadActualTurista = $this->dataBase->query();
        $cantidadActualTurista = $this->dataBase->query();

        if ($existe != null) {
            // verificar la cantidad de asientos y la clase
            if ($this->dataBase->getCantidadAsientos($idReserva, $butaca) < $cantidadAsientos) {
                $contadorErrores++;
                $_SESSION['errorNoHayAciento'];
            }
            if ($contadorErrores == 0) {
                if ($butaca == 'turista') {
                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsiario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','','$origen','$destino')");
                } elseif ($butaca == 'ejecutiva') {
                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsiario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','','$origen','$destino')");
                } elseif ($butaca == 'primera') {
                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsiario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva) 
                                                values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','','$origen','$destino')");
                }

            }
            //        Calcularlo
            //$diaLlegada = $_POST["diaLlegada"] ?? "";
            //$horaLlegada = $_POST["horaLlegada"] ?? "";

        }
    }
}