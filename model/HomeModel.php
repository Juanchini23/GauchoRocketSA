<?php

class HomeModel
{


    private $database;

    public function __construct($database)
    {

        $this->database = $database;
    }

    public function busquedaVuelos($origen, $dia)
    {
        $diaLetra = "";

        switch ($dia) {
            case "Monday":
                $diaLetra = "L";
                break;

            case "Tuesday":
                $diaLetra = "M";
                break;

            case "Wednesday":
                $diaLetra = "X";
                break;

            case "Thursday":
                $diaLetra = "J";
                break;

            case "Friday":
                $diaLetra = "V";
                break;

            case "Saturday":
                $diaLetra = "S";
                break;

            case "Sunday":
                $diaLetra = "D";
                break;

            default:
                break;
        }

        if (strlen($diaLetra) == null || strlen($origen) == null) {
            return $this->database->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE (o.descripcion = '$origen'
OR p.dia = '$diaLetra')
AND (tv.descripcion = 'EntreDestinosUno' || tv.descripcion = 'EntreDestinosDos' )");
        } else {
            return $this->database->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE (o.descripcion = '$origen'
AND p.dia = '$diaLetra')
AND (tv.descripcion = 'EntreDestinosUno' || tv.descripcion = 'EntreDestinosDos' )");
        }
    }

    public function solicitarNombreUsuario()
    {
        $usurios = $this->database->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio) {
            return $usurio["nombre"];
        }
    }

    public function getEspecificacion($id)
    {
        return $this->database->query("");

    }


}

