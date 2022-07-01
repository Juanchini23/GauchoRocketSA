<?php

class HomeModel
{


    private $dataBase;

    public function __construct($dataBase)
    {

        $this->dataBase = $dataBase;
    }

    public function busquedaVuelos($origen, $dia, $codigoViajero, $destino)
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

        if (strlen($diaLetra) == null || strlen($origen) == null) {

            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
FROM planificacion p
         JOIN lugar l ON p.idOrigen = l.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
         JOIN tipoCliente tc ON m.tipoCliente = tc.id
WHERE (l.descripcion = '$origen'
OR p.dia = '$diaLetra')
AND (tv.descripcion = 'EntreDestinosUno' || tv.descripcion = 'EntreDestinosDos' )
AND tc.descripcion like '%$codigoViajero%'");
        } else {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', l.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
FROM planificacion p
         JOIN lugar l ON p.idOrigen = l.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
         JOIN tipoCliente tc ON m.tipoCliente = tc.id
WHERE (l.descripcion = '$origen'
AND p.dia = '$diaLetra')
AND (tv.descripcion = 'EntreDestinosUno' || tv.descripcion = 'EntreDestinosDos' )
AND tc.descripcion like '%$codigoViajero%'");

        }
    }

    public function busquedaVuelosOrigen($origen,$codigoviajero){

        $origenId = $this->getIdLugar($origen);

        $resultado =  $this->dataBase->query("SELECT p.id as 'id' FROM reserva r
                                        JOIN planificacion p ON r.idPlanificacion = p.id
                                        JOIN modelo m ON p.idModelo = m.id
                                        JOIN tipoCliente tc ON m.tipoCliente = tc.id
                                        WHERE tc.descripcion like '%$codigoviajero%'
                                        AND r.idOrigenReserva = '$origenId'");

        return $this->dataBase->getPlani($resultado[0]["id"]);
    }

    public function solicitarNombreUsuario()
    {
        $usurios = $this->dataBase->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio) {
            return $usurio["nombre"];
        }
    }

    public function getEspecificacion($id)
    {
        return $this->dataBase->query("");

    }

    public function getReservas($id)
    {
        return $this->dataBase->query("SELECT rC.fecha AS 'fecha', p.horaPartida AS 'hora', lO.descripcion AS 'origen', lD.descripcion AS 'destino', rC.id AS 'id', 
                                       eR.descripcion AS 'estado' , IF(eR.descripcion = 'Pendiente', 1, 0) AS 'estadoBool'
                                        FROM reservacompleta rC
                                                 JOIN planificacion p ON rC.idPlanificacion = p.id
                                                 JOIN lugar lO ON rC.idOrigen = lO.id
                                                 JOIN lugar lD ON rC.idDestino = lD.id
                                                 JOIN estadoreserva eR ON eR.id = rC.idEstadoReserva
                                        WHERE rC.idUsuario = '$id'");

    }

    public function getCosas($idPlanificacion){
        //trae bien
        return $this->dataBase->query("SELECT tv.descripcion as 'vuelo', te.descripcion as 'equipo'
                                        FROM planificacion p JOIN tipoVuelo tv ON p.idTipoVuelo = tv.id
                                        JOIN modelo m ON p.idModelo = m.id
                                        JOIN tipoEquipo te ON m.tipoEquipo = te.id
                                        WHERE p.id = '$idPlanificacion'");
    }

    public function getTipoEquipo($idPlanificacion){
        // trae bien
        return $this->dataBase->query("SELECT te.descripcion as 'equipo' FROM planificacion p 
                                       JOIN modelo m ON p.idModelo = m.id
                                       JOIN tipoEquipo te ON m.tipoEquipo = te.id
                                       WHERE p.id ='$idPlanificacion'");
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

