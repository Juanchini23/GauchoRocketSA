<?php

require 'third-party/phpqrcode/qrlib.php';
require_once 'public/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class HomeController
{
    private $printer;

    private $circuitoUnoBA = array(["Tierra" => 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra" => 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra" => 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra" => 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);
    private $cantidadDia = 0;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute()
    {
        $data = Validator::validarSesion();
        $codigoviajero = $_SESSION["codigoViajero"] ?? "";
        if (isset($_SESSION["origen"]) && isset($_SESSION["fecha"]) && isset($_SESSION["destino"]) && isset($_SESSION["codigoViajero"])) {
            $dia = date('l', strtotime($_SESSION["fecha"]));
            $codigoviajero = $_SESSION["codigoViajero"];
            $localStorage = $this->homeModel->busquedaVuelos($_SESSION["origen"], $dia, $codigoviajero, $_SESSION["destino"]);
            $data["planificacion"] = $localStorage;
            $data["destino"] = $_SESSION["destino"];
        }
        $_SESSION['errorNoHayAciento'] = 0;
        $this->printer->generateView('homeView.html', $data);
    }

    public function busqueda()
    {
        $data = Validator::validarSesion();

        $codigoviajero = $_SESSION["codigoViajero"] ?? 3;
        $origen = $_POST["origen"] ?? "";
        $fecha = $_POST["fecha"] ?? "";
        $destino = $_POST["destinoVuelo"] ?? "";

        // LocalStorage
        $_SESSION["origen"] = $origen;
        $_SESSION["fecha"] = $fecha;
        $_SESSION["destino"] = $destino;
        // /LocalStorage


        // como saber el dia de la semana que es la fecha que nos llega desde el formulario de entredestinos
        $dia = date('l', strtotime($fecha));

        // si viene destino diferente a BA tiene que buscar planificaciones que segun la suma de la hora de salida
        // mas lo que tarda en llegar ahi matcheen bien con el dia de salida de la planificacion
        // hacer query en base a una query anterior para agarrar la query con la hora correcta
        // hacer calculos de horaa y dia en php y no js
        if ($origen == 'BA' || $origen == 'AK') {
            //ver si el destino que ponemos pasa por donde debe
            $respuesta = $this->homeModel->busquedaVuelos($origen, $dia, $codigoviajero, $destino);
            $data["planificacion"] = $respuesta;
        } else {

            // tiene dia hora y origen
            $planificacion = $this->homeModel->busquedaVuelosOrigen($origen, $codigoviajero) ?? "";
            $i= 0;
            foreach ($planificacion as $plani){
                $horaPlani = $plani[0]["hora"] ?? "";
                $diaPlani = $plani[0]["dia"] ?? "";

                $horaTarda = $this->getHoraTarda($origen, $plani[0]["tipoVuelo"], $plani[0]["id"]) ?? "";
                $horaFinal = $this->getHoraFinal($horaPlani, $horaTarda) ?? "";
                $diaFinal = $this->getDiaFinal($diaPlani) ?? "";
                $reemplazo = array("hora" => $horaFinal);
                $reemplazo2 = array("dia" => $diaFinal);
                //$reemplazo3 = array("origen", $origen);
                $planificacion[$i] = array_replace($plani[0], $reemplazo, $reemplazo2);
                $data["destino"] = $destino;
                if(!$this->chequearDestinos($origen, $destino, $plani[0]["tipoVuelo"], $this->homeModel->getTipoEquipo($plani[0]["id"])[0]["equipo"])){
                    unset($planificacion[$i]);
                }
                $i++;
            }

            $data["planificacion"] = $planificacion;
        }

        //ver poner la fecha que viene de las planificaciones que traigo en la busqueda
        $data["fecha"] = $fecha;
        $data["destino"] = $destino;
        $this->printer->generateView('homeView.html', $data);
    }

    public function misReservas()
    {
        $data = Validator::validarSesion();

        $misReservas = $this->homeModel->getReservas($_SESSION["idUserLog"]);

        $data["reservas"] = $misReservas;

        $this->printer->generateView('misReservasView.html', $data);
    }

    public function datos()
    {
        $data = Validator::validarSesion();

        $this->printer->generateView('adminView.html', $data);
    }

    //para despues de chequear las planificacion que el destino pertenezca al tipo de vuelo de la planificacion elegida
    private function chequearDestinos($origen, $destino, $tipoVuelo, $equipo){
        $keyCircuitoUnoBA = array_keys($this->circuitoUnoBA[0]);
        $keyCircuitoUnoAA = array_keys($this->circuitoUnoAA[0]);
        $keyCircuitoDosBA = array_keys($this->circuitoDosBA[0]);
        $keyCircuitoDosAA = array_keys($this->circuitoDosAA[0]);

         switch ($tipoVuelo){

             case 'EntreDestinosUno':
                 if ($equipo == 'BA'){
                    if(in_array($origen, $keyCircuitoUnoBA) && in_array($destino, $keyCircuitoUnoBA)){
                        return true;
                    } else return false;
                 } else if($equipo == 'AA'){
                     if(in_array($origen, $keyCircuitoUnoAA) && in_array($destino, $keyCircuitoUnoAA)){
                         return true;
                     }else return false;
                 }
                 break;

             case 'EntreDestinosDos':
                 if ($equipo == 'BA'){
                     if(in_array($origen, $keyCircuitoDosBA) && in_array($destino, $keyCircuitoDosBA)){
                         return true;
                     }else return false;
                 } else if($equipo == 'AA'){
                     if(in_array($origen, $keyCircuitoDosAA) && in_array($destino, $keyCircuitoDosAA)){
                         return true;
                     }else return false;
                 }
                 break;

             default: return false;
         }
    }

    private function getHoraTarda($origen, $tipoVuelo, $idPlanificacion)
    {
        $equipo = $this->homeModel->getTipoEquipo($idPlanificacion)[0]["equipo"];
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


    public function qr()
    {
        $fecha = $_GET["fecha"] ?? "";

        $fechaCalculada = date_create($fecha);
        date_add($fechaCalculada, date_interval_create_from_date_string("-2 days"));
        $fechaCalculada= date_format($fechaCalculada, "Y-m-d");

        $fechaActual = date("Y-m-d");

        if($fechaCalculada > $fechaActual){
            $id = $_GET['id'] ?? "";

            $data = Validator::validarSesion();

            $miReserva = $this->homeModel->getMiReserva($id);

            $data["miReserva"] = $miReserva;
            $data["id"] = $id;
            $data["erroCheckIN"] = "No puede realizar el Check-In con una anticipacion a 48 hs";
            $this->printer->generateView('miReservaView.html', $data);

        }else{

            $data = Validator::validarSesion();
            $id = $_GET['id'] ?? ""; // id de la reserva
            $path = 'public/qr/';
            $ruta_qr = $path.uniqid().".png";
            $text = Validator::generarCodigo();

            $tamaño = 10;
            $framSize = 3;

            QRcode::png($text,$ruta_qr,QR_ECLEVEL_H,$tamaño,$framSize);

            $data["qr"] = $ruta_qr;

            $this->homeModel->cambiarEstadoReserva($id);


            $this->printer->generateView('descargarQRView.html', $data);

        }



    }


    public function descargarQr()
    {

            $qr = $_GET["qr"] ?? "";
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

            <img src="<?php echo $qr; ?>">
            <h1>Pasajero: <span><?php echo $_SESSION["apellido"] . ", " . $_SESSION["usuario"]; ?></span></h1>
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
            $dompdf->stream("descargarQr.pdf", ['Attachment' => 1]);






    }


}