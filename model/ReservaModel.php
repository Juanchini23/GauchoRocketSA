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

    public function generarReserva($origen, $destino, $diaSalida, $horaSalida, $butaca, $cantidadAsientos, $metodoPago, $idUser, $idPlanificacion, $fecha, $idServicio)
    {

        $planificacion = $this->dataBase->getPlani($idPlanificacion);


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

        $cosas = $this->dataBase->query("SELECT tv.descripcion as 'vuelo', te.descripcion as 'equipo'
                                        FROM planificacion p JOIN tipoVuelo tv ON p.idTipoVuelo = tv.id
                                        JOIN modelo m ON p.idModelo = m.id
                                        JOIN tipoEquipo te ON m.tipoEquipo = te.id
                                        WHERE p.id = '$idPlanificacion'");
        $precio = 0;
        switch ($cosas[0]["vuelo"]) {
            case 'EntreDestinosUno':
                if ($cosas[0]["equipo"] == 'BA') {
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
                            if ($sumaAsientos > $cantidadM) {
                                $_SESSION['errorNoHayAciento'] = 1;
                                header("location:/");
                                exit();
                            } else {
                                $o = $this->getIdLugar($keyCircuitoUnoBA[$i]);
                                $d = $this->getIdLugar($keyCircuitoUnoBA[$i + 1]);
                                if ($butaca == 'turista') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 100;
                                } elseif ($butaca == 'ejecutivo') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 300;
                                } elseif ($butaca == 'primera') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 800;
                                }
                            }
                        }
                    } while ($keyCircuitoUnoBA[$i + 1] != $destino);
                } else if ($cosas[0]["equipo"] == 'AA') {
                    $keyCircuitoUnoAA = array_keys($this->circuitoUnoAA[0]);

                    $entro = false;
                    if ($origen == 'AK' || $origen == 'BA') {
                        $origen = 'Tierra';
                    }
                    $i = -1;
                    do {
                        $i++;
                        if ($keyCircuitoUnoAA[$i] == $origen) {
                            $entro = true;
                        }
                        if ($entro == true) {
                            // verificar la cantidad de asientos y la clase
                            if ($sumaAsientos > $cantidadM) {
                                $_SESSION['errorNoHayAciento'] = 1;
                                header("location:/");
                                exit();
                            } else {
                                $o = $this->getIdLugar($keyCircuitoUnoAA[$i]);
                                $d = $this->getIdLugar($keyCircuitoUnoAA[$i + 1]);
                                if ($butaca == 'turista') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 150;
                                } elseif ($butaca == 'ejecutivo') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 350;
                                } elseif ($butaca == 'primera') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 850;
                                }
                            }
                        }
                    } while ($keyCircuitoUnoAA[$i + 1] != $destino);
                }
                break;

            case 'EntreDestinosDos':

                if ($cosas[0]["equipo"] == 'BA') {
                    $keyCircuitoDosBA = array_keys($this->circuitoDosBA[0]);

                    $entro = false;
                    if ($origen == 'AK' || $origen == 'BA') {
                        $origen = 'Tierra';
                    }
                    $i = -1;
                    do {
                        $i++;
                        if ($keyCircuitoDosBA[$i] == $origen) {
                            $entro = true;
                        }
                        if ($entro == true) {
                            // verificar la cantidad de asientos y la clase
                            if ($sumaAsientos > $cantidadM) {
                                $_SESSION['errorNoHayAciento'] = 1;
                                header("location:/");
                                exit();
                            } else {
                                $o = $this->getIdLugar($keyCircuitoDosBA[$i]);
                                $d = $this->getIdLugar($keyCircuitoDosBA[$i + 1]);
                                if ($butaca == 'turista') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 200;
                                } elseif ($butaca == 'ejecutivo') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 600;
                                } elseif ($butaca == 'primera') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 900;
                                }
                            }
                        }
                    } while ($keyCircuitoDosBA[$i + 1] != $destino);
                } else if ($cosas[0]["equipo"] == 'AA') {
                    $keyCircuitoDosAA = array_keys($this->circuitoDosAA[0]);

                    $entro = false;
                    if ($origen == 'AK' || $origen == 'BA') {
                        $origen = 'Tierra';
                    }
                    $i = -1;
                    do {
                        $i++;
                        if ($keyCircuitoDosAA[$i] == $origen) {
                            $entro = true;
                        }
                        if ($entro == true) {
                            // verificar la cantidad de asientos y la clase
                            if ($sumaAsientos > $cantidadM) {
                                $_SESSION['errorNoHayAciento'] = 1;
                                header("location:/");
                                exit();
                            } else {
                                $o = $this->getIdLugar($keyCircuitoDosAA[$i]);
                                $d = $this->getIdLugar($keyCircuitoDosAA[$i + 1]);
                                if ($butaca == 'turista') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 250;
                                } elseif ($butaca == 'ejecutivo') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 650;
                                } elseif ($butaca == 'primera') {
                                    $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                                    $precio += 1000;
                                }
                            }
                        }
                    } while ($keyCircuitoDosAA[$i + 1] != $destino);
                }
                break;

            case 'Orbitales':
                if ($origen == 'AK' || $origen == 'BA') {
                    $origen = 'Tierra';
                }
                if ($sumaAsientos >= $cantidadM) {
                    $_SESSION['errorNoHayAciento'] = 1;
                    header("location:/");
                    exit();
                } else {
                    $o = $this->getIdLugar($origen);
                    $d = $this->getIdLugar($origen);
                    if ($butaca == 'turista') {
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values('$cantidadAsientos',0,0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                        $precio = 1800;
                    } elseif ($butaca == 'ejecutivo') {
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,'$cantidadAsientos',0,'$idUser','$idPlanificacion','$fecha','$o','$d');");
                        $precio = 2300;
                    } elseif ($butaca == 'primera') {
                        $this->dataBase->reservar("insert into reserva(turista, ejecutivo, primera , idUsuario, idPlanificacion, fecha, idOrigenReserva, idDestinoReserva)
                                            values(0,0,'$cantidadAsientos','$idUser','$idPlanificacion','$fecha','$o','$d');");
                        $precio = 2900;
                    }
                }
                break;
            default:
        }

        if ($origen == 'AK' || $origen == 'BA') {
            $origen = 'Tierra';
        }

        $or = $this->getIdLugar($origen);
        $de = $this->getIdLugar($destino);
        $precioServicio = $this->dataBase->query("SELECT precio as 'precio' FROM servicio WHERE id = '$idServicio'");
        $pr = $precioServicio[0]["precio"];
        $precioFinal = ($precio * $cantidadAsientos) + $pr;

        if ($cosas[0]["vuelo"] == 'EntreDestinosUno' || $cosas[0]["vuelo"] == 'EntreDestinosDos') {
            $this->dataBase->guardarEntera("INSERT INTO reservaCompleta (idUsuario, idPlanificacion, fecha, idOrigen, idDestino, idEstadoReserva, precio, idServicio)
                                        VALUES ('$idUser', '$idPlanificacion', '$fecha', '$or', '$de', 1, '$precioFinal', '$idServicio')");
        } else if ($cosas[0]["vuelo"] == 'Orbitales') {
            $this->dataBase->guardarEntera("INSERT INTO reservaCompleta (idUsuario, idPlanificacion, fecha, idOrigen, idDestino, idEstadoReserva, precio, idServicio)
                                        VALUES ('$idUser', '$idPlanificacion', '$fecha', '$or', '$or', 1, '$precioFinal', '$idServicio')");
        }


    }

    public function isOrbital($idPlanificacion)
    {
        $resultado = $this->dataBase->query("SELECT * FROM planificacion p 
                                            WHERE p.idTipoVuelo = 1 AND p.id = '$idPlanificacion'");

        return $resultado != null;
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

    public function getCantidadAsientosReservados($id, $fecha)
    {
        return $this->dataBase->query("SELECT SUM(turista) AS 'turista', SUM(ejecutivo) AS 'ejecutivo', SUM(primera) AS 'primera' 
                                FROM reserva r 
                               WHERE r.idPlanificacion = '$id' AND r.fecha = '$fecha';");
    }

    public function getMiReserva($id)
    {
        return $this->dataBase->query("SELECT rC.fecha AS 'fecha', p.horaPartida AS 'hora', lO.descripcion AS 'origen', lD.descripcion AS 'destino',
                                       rC.id AS 'id', s.descripcion AS 'servicio', rC.precio AS 'precio', eR.descripcion AS 'estado' , IF(eR.descripcion = 'Pendiente', 1, 0) AS 'estadoBool'
                                       FROM reservacompleta rC 
                                       JOIN planificacion p ON rC.idPlanificacion = p.id
                                       JOIN lugar lO ON rC.idOrigen = lO.id
                                       JOIN lugar lD ON rC.idDestino = lD.id    
                                       JOIN servicio s ON s.id = rC.idServicio
                                       JOIN estadoreserva eR ON eR.id = rC.idEstadoReserva
                                       WHERE rC.id = '$id';");

    }

    public function setPago($id)
    {
        $this->dataBase->pagarReserva($id);
    }

    public function setCheckIn($id)
    {
        $this->dataBase->chequearReserva($id);
    }
}