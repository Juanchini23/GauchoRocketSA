<?php
require_once 'public/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class ReservaController
{

    private $reservaModel;
    private $printer;

    private $circuitoUnoBA = array(["Tierra" => 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra" => 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra" => 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra" => 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);
    private $cantidadDia = 0;

    public function __construct($reservaModel, $getPrinter)
    {
        $this->reservaModel = $reservaModel;
        $this->printer = $getPrinter;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

        $id = $_GET["id"] ?? "";
        $fechaViaje = $_SESSION["fecha"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $hora = $_GET["hora"] ?? "";

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido
        if ($this->reservaModel->isOrbital($id)) {
            $data["orbital"] = 1;
        }


        $planificacion = $this->reservaModel->getPlanificacion($id);
        $datosModelo = $this->reservaModel->getDatosModelo($id);
        $datosAsientos = $this->reservaModel->getCantidadAsientosReservados($id, $fechaViaje);

        $destino = $_SESSION["destino"] ?? "";
        $origen = $_SESSION["origen"] ?? "";

        // calcular dia llegada hora llegada /////////////////////
        if($planificacion[0]["tipoVuelo"] == "EntreDestinosUno" || $planificacion[0]["tipoVuelo"] == "EntreDestinosDos"){
            $horaTarda = $this->getHoraTarda($destino, $planificacion[0]["tipoVuelo"], $planificacion[0]["id"]) ?? "";
            $horaFinal = $this->getHoraFinal($hora, $horaTarda) ?? "";
            $diaFinal = $this->getDiaFinal($planificacion[0]["dia"]) ?? "";

            $data["diaLlegada"] = $diaFinal;
            $data["horaLlegada"] = $horaFinal;
        } else if($planificacion[0]["tipoVuelo"] == "Orbitales"){
            $horaTarda = 8;
            $horaFinal = $this->getHoraFinal($hora, $horaTarda) ?? "";
            $diaFinal = $this->getDiaFinal($planificacion[0]["dia"]) ?? "";

            $data["diaLlegada"] = $diaFinal;
            $data["horaLlegada"] = $horaFinal;
        }

        /////////////////////////////////////////////////////////

        $reemplazo = array("origen" => $origen);
        $planificacion[0] = array_replace($planificacion[0], $reemplazo);

        $data["asientoTurista"] = $datosModelo[0]["turista"] - $datosAsientos[0]["turista"];
        $data["asientoEjecutivo"] = $datosModelo[0]["ejecutivo"] - $datosAsientos[0]["ejecutivo"];
        $data["asientoPrimera"] = $datosModelo[0]["primera"] - $datosAsientos[0]["primera"];
        $data["idPlanificacion"] = $id;
        $data["datosModelo"] = $datosModelo;
        $data["planificacion"] = $planificacion;
        $data["destinoVuelo"] = $destino;
        $data["fechaSalida"] = $fechaViaje;

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservar()
    {
        $origen = $_POST["origen"] ?? "";
        $destino = $_POST["destinoVuelo"] ?? "";
        $diaSalida = $_POST["diaSalida"] ?? "";
        $horaSalida = $_POST["horaSalida"] ?? "";
        $butaca = $_POST["butaca"] ?? "";
        $cantidadAsientos = $_POST["cantidadAsientos"] ?? "";
        $metodoPago = $_POST["metodoPago"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $idPlanificacion = $_POST["idPlanificacion"] ?? "";
        $fechaSalida = $_POST["fechaSalida"] ?? "";
        $idServicio = $_POST["servicio"] ?? "";

        // Generar una reserva
        $precio = $this->reservaModel->generarReserva($origen, $destino, $diaSalida, $horaSalida, $butaca, $cantidadAsientos, $metodoPago, $idUser, $idPlanificacion, $fechaSalida, $idServicio);

        $totalApagar = $precio;

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start()
        ?>
        <!doctype html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible">
            <meta name="viewport" content="width=device-width, initial-scale=1">

        </head>

        <body>

        <h1>Felicitaciones <span><?php echo $_SESSION["apellido"] . ", " . $_SESSION["usuario"]; ?></span>!!!</h1>
        <h3>Datos:</h3>
        <p>Viajero:<strong><?php echo $_SESSION["apellido"] . ", " . $_SESSION["usuario"]; ?></strong></p>
        <p>Te vas el dia:<strong><?php echo " " . $diaSalida . " " . $fechaSalida . " "; ?> a
                las: <?php echo " " . $horaSalida . " "; ?></strong>
            hs </p>

        <p>Reservaste:<strong><?php echo " " . $cantidadAsientos . " "; ?></strong> butaca/s </p>


        <p>Total a pagar:<strong><?php echo "USD " . $totalApagar; ?></strong></p>


        <p>A preparar las valijas!</p>
        <p>Buen viaje te desea tu compania amiga <strong>GAUCHO ROCKET!</strong></p>


        <?php

        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";

        //dia que se genera el PDF
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        echo "PDF generado el: " . date("d-m-Y h:i:sa");

        ?>

        </body>
        </html>
        <?php
        $html = ob_get_clean();


        $dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser
        $dompdf->stream("ReservaVuelo.pdf", ['Attachment' => 1]);

        header("location: /");
        exit();
    }

    public function verReserva()
    {
        $id = $_GET['id'] ?? "";

        $data = Validator::validarSesion();

        $miReserva = $this->reservaModel->getMiReserva($id);

        $data["miReserva"] = $miReserva;
        $data["id"] = $id;

        $this->printer->generateView('miReservaView.html', $data);
    }

    public function pagar()
    {
        $id = $_POST["id"];
        $this->reservaModel->setPago($id);

        header("location: /home/misReservas");
        exit();
    }

    public function checkin()
    {
        $id = $_POST["id"];

        //cmbia el estado a chequeado
        $this->reservaModel->setCheckIn($id);

    }

    private function getHoraTarda($origen, $tipoVuelo, $idPlanificacion){
        $equipo = $this->reservaModel->getTipoEquipo($idPlanificacion)[0]["equipo"];
        if ($tipoVuelo == 'EntreDestinosUno') {
            if ($equipo == 'BA') {
                return $this->circuitoUnoBA[0][$origen];
            } else if ($equipo == 'AA') {
                return $this->circuitoUnoAA[0][$origen];
            }
        } else if ($tipoVuelo == 'EntreDestinosDos') {
            if ($equipo == 'BA') {
                return $this->circuitoDosBA[0][$origen];
            } else if ($equipo == 'AA') {
                return $this->circuitoDosAA[0][$origen];
            }
        }
    }

    private function getHoraFinal($horaPlani, $horaTarda)
    {
        $suma = $horaPlani + $horaTarda;
        if ($suma < 24) {
            $this->cantidadDia = 0;
            return $suma;
        }
        if ($suma == 24) {
            $this->cantidadDia = 1;
            return 0;
        }

        if ($suma > 24 && $suma < 48) {
            $this->cantidadDia = 1;
            return $suma - 24;
        }

        if ($suma > 48) {
            $this->cantidadDia = 2;
            return $suma - 48;
        }
    }

    private function getDiaFinal($diaPlani)
    {
        if ($this->cantidadDia == 0) {
            return $diaPlani;
        }
        if ($this->cantidadDia == 1) {
            switch ($diaPlani) {
                case "Lunes":
                    return "Martes";
                case "Martes":
                    return "Miercoles";
                case "Miercoles":
                    return "Jueves";
                case "Jueves":
                    return "Viernes";
                case "Viernes":
                    return "Sabado";
                case "Sabado":
                    return "Domingo";
                case "Domingo":
                    return "Lunes";
            }
        }

        if ($this->cantidadDia == 2) {
            switch ($diaPlani) {
                case "Lunes":
                    return "Miercoles";
                case "Martes":
                    return "Jueves";
                case "Miercoles":
                    return "Viernes";
                case "Jueves":
                    return "Sabado";
                case "Viernes":
                    return "Domingo";
                case "Sabado":
                    return "Lunes";
                case "Domingo":
                    return "Martes";
            }
        }

    }
}