<?php

use Dompdf\Dompdf;
require_once 'public/dompdf/autoload.inc.php';

class AdminController
{

    private $printer;

    public function __construct($adminModel, $printer)
    {
        $this->printer = $printer;
        $this->adminModel = $adminModel;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

//        Tasa de ocupacion por tipo viaje

        $tOcupacionPorViajeOrbital = $this->adminModel->getTOcupacionPorviaje(1, 'orbitales');
        $data["orbitales"] = $tOcupacionPorViajeOrbital[0]['orbitales'];

        $tOcupacionPorViajeTour = $this->adminModel->getTOcupacionPorviaje(2, 'tour');
        $data["tour"] = $tOcupacionPorViajeTour[0]['tour'];

        $tOcupacionPorViajeCircuitoUno = $this->adminModel->getTOcupacionPorviaje(3, 'circuitoUno');
        $data["circuitoUno"] = $tOcupacionPorViajeCircuitoUno[0]['circuitoUno'];

        $tOcupacionPorViajeCircuitoDos = $this->adminModel->getTOcupacionPorviaje(4, 'circuitoDos');
        $data["circuitoDos"] = $tOcupacionPorViajeCircuitoDos[0]['circuitoDos'];

//        /Tasa de ocupacion por tipo viaje

//        Tasa de ocupacion por equipo

        $tOcupacionPorTipoViajeOrbital = $this->adminModel->getTOcupacionPorTipoViaje('OR', 'orbitalesOr');
        $data["orbitalesOr"] = $tOcupacionPorTipoViajeOrbital[0]["orbitalesOr"];

        $tOcupacionPorTipoViajeBajaAceleracion = $this->adminModel->getTOcupacionPorTipoViaje('BA', 'bajaAceleracion');
        $data["bajaAceleracion"] = $tOcupacionPorTipoViajeBajaAceleracion[0]["bajaAceleracion"];

        $tOcupacionPorTipoViajeAltaAceleracion = $this->adminModel->getTOcupacionPorTipoViaje('AA', 'altaAceleracion');
        $data["altaAceleracion"] = $tOcupacionPorTipoViajeAltaAceleracion[0]["altaAceleracion"];

//        /Tasa de ocupacion por equipo

//        Cabinas
        $CantCabinaTurista = $this->adminModel->getCabinaTurita('turista');
        $data["cabinaTurista"] = $CantCabinaTurista[0]["turista"];

        $CantCabinaEjecutivo = $this->adminModel->getCabinaTurita('ejecutivo');
        $data["cabinaEjecutiva"] = $CantCabinaEjecutivo[0]["ejecutivo"];

        $CantCabinaPrimera = $this->adminModel->getCabinaTurita('primera');
        $data["cabinaPrimera"] = $CantCabinaPrimera[0]["primera"];
//        /Cabinas

//        Facturacion mensual

        $mesActual = $this->adminModel->getMesActual();
        $data["mesActual"] = $mesActual;
        $facturacionMensual = $this->adminModel->getFacturacionMensual();
        $data["facturacionMensual"] = $facturacionMensual[0]["facturacionMensual"];

//        /Facturacion mensual


        $this->printer->generateView('adminView.html', $data);
    }

    public function imprimirTipoViaje()
    {
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
        <h3>Orbitales <?php echo $_GET["or"] ?></h3>
        <h3>Tour <?php echo $_GET["to"] ?></h3>
        <h3>Cicuito uno <?php echo $_GET["un"] ?></h3>
        <h3>Circuito dos <?php echo $_GET["do"] ?></h3>


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

        header("location: /admin");
        exit();
    }

    public function imprimirFacturacion()
    {

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
        <h3>La facturacion mensual de  <?php echo $_GET["mesActual"] ?> es de <?php echo $_GET["facturacionMensual"] ?></h3>


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


    }

}