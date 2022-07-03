<?php
require_once 'public/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class TourController
{

    private $tourModel;
    private $printer;

    public function __construct($tourModel, $printer)
    {
        $this->tourModel = $tourModel;
        $this->printer = $printer;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

        if($_SESSION['errorNoHayAciento']==1){
            $data["errorSobrecargado"] = "No hay disponibilidad de la cantidad de asientos solicitados ";
        }

        $this->printer->generateView('tourView.html', $data);
    }

    public function busqueda()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }
        $_SESSION['errorNoHayAciento'] = 0;
        $fecha = $_POST["fecha"] ?? "";

        $codigoviajero = $_SESSION["codigoViajero"] ?? "";

        $data["fecha"] = $fecha;
        $timestamp = strtotime($fecha);
        $timestamp = idate('w', $timestamp); // aca si es domingo devuelve 0

        if ($timestamp == 0) {
            $dia = "Domingo";
            $respuesta = $this->tourModel->getTours($dia, $codigoviajero);
            $data["tours"] = $respuesta;

            if($respuesta == null){
                $data["sinDatosTours"] = "No hay vuelos disponibles para vos el dia seleccionado";
            }

            $this->printer->generateView('tourView.html', $data);
        } else{
            $data["sinDatosTours"] = "Ups! No hay vuelos para el dia seleccionado!";
            $this->printer->generateView('tourView.html', $data);
        }

    }

    //NO LO USO MAS
//    public function busquedaTodosLosTours()
//    {
//        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
//            $data["loggeado"] = 1;
//            $data["nombre"] = $_SESSION["usuario"];
//        }
//
//        $respuesta = $this->tourModel->getTodosLosTours();
//
//        if ($respuesta) {
//            $data["tours"] = $respuesta;
//        } else {
//            $data["sinDatosTours"] = "Ups! No hay vuelos!";
//        }
//
//
//        $this->printer->generateView('tourView.html', $data);
//    }


    public function confirmarVueloTour()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
        }

        $id = $_GET["id"] ?? "";
        $fechaViaje = $_GET["fecha"] ?? "";
        $_SESSION['errorNoHayAciento'] = 0;
        $planificacion = $this->tourModel->getPlanificacion($id);
        $datosModelo = $this->tourModel->getDatosModelo($id);


        //aca agarro la fecha que selecciono en el calendario y le sumo los 35 dias que dura el tour
        $llegada = date_create($fechaViaje);
        date_add($llegada, date_interval_create_from_date_string("35 days"));
        $llegada = date_format($llegada, "Y-m-d");

        $datosAsientos = $this->tourModel->getCantidadAsientosReservados($id, $fechaViaje);

        $data["asientoPrimera"] = $datosModelo[0]["primera"] - $datosAsientos[0]["primera"];

        if($data["asientoPrimera"] == 0){
            $data["VueloLLeno"] = "Vuelo lleno";
        }

        //cargo los datos para la vista
        $data["id"] = $id;
        $data["fecha"] = $fechaViaje;
        $data["llegada"] = $llegada;
        $data["planificacion"] = $planificacion;
        $data["datosModelo"] = $datosModelo;

        $this->printer->generateView('confirmarVueloTour.html', $data);
    }

    public function generarPDF()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
        }
        $_SESSION['errorNoHayAciento'] = 0;
        $idPlanificacion = $_GET["id"] ?? "";
        $cantidadAsientos = $_POST["cantidadAsientos"] ?? "";

        $butaca = $_POST["butaca"] ?? "";
        $dia = $_GET["dia"] ?? "";
        $fechaSalida = $_GET["fecha"] ?? "";
        $hora = $_GET["hora"] ?? "";
        $llegada = $_GET["llegada"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $origen = $_GET["origen"] ?? "";
        $idServicio = $_POST["servicio"] ?? "";

        $asientosDisponibles = $_GET["primera"] ?? "";
        //$claseDeViaje = $_POST["claseDeViaje"] ?? "";   esto me llega vacio




        $precio = $this->tourModel->reservaTour($origen, $butaca, $cantidadAsientos, $idUser, $idPlanificacion, $fechaSalida, $idServicio);

        
        $totalApagar= $cantidadAsientos * $precio;


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
        <p>Te vas el dia:<strong><?php echo " " . $dia . " " . $fechaSalida . " "; ?> a las: <?php echo " " . $hora . " "; ?></strong>
            hs </p>
        <p>Volves el dia:<strong><?php echo " " . $llegada; ?></strong></p>


        <p>Reservaste:<strong><?php echo " " . $cantidadAsientos . " "; ?></strong> butaca/s </p>


        <p>Total a pagar:<strong><?php echo "USD " . $totalApagar; ?></strong></p>


        <p>A preparar las valijas!</p>
        <p>Buen viaje te desea tu compania amiga <strong>GAUCHO ROCKET!</strong></p>


        <?php

        //esto por si necesito un dato de planificacion o modelo lo tengo que iterar
        //        foreach ($planificacion as $row) {
        //            echo $row['modelo'];
        //        }
        //        echo "<br>";


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
        $dompdf->stream("ReservaVueloTour.pdf", ['Attachment' => 0]);

    }
}

?>






