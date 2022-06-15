<?php

class HomeModel
{


    private $dataBase;

    public function __construct($dataBase)
    {

        $this->dataBase = $dataBase;
    }

    public function busquedaVuelos($origen, $dia)
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

            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
FROM planificacion p
         JOIN origen o ON p.idOrigen = o.id
         JOIN modelo m ON p.idModelo = m.id
         JOIN nave n ON m.idNave = n.id
         JOIN tipoVuelo tv ON tv.id = p.idTipoVuelo
WHERE (o.descripcion = '$origen'
OR p.dia = '$diaLetra')
AND (tv.descripcion = 'EntreDestinosUno' || tv.descripcion = 'EntreDestinosDos' )");
        } else {
            return $this->dataBase->query("SELECT p.id, p.dia as 'dia', p.horaPartida as 'hora', o.descripcion as 'origen', n.modelo as 'modelo', tv.descripcion as 'tipoVuelo'
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
        $usurios = $this->dataBase->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio) {
            return $usurio["nombre"];
        }
    }

    public function getEspecificacion($id)
    {
        return $this->dataBase->query("");

    }

}

