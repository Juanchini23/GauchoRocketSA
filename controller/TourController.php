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

        $this->printer->generateView('tourView.html', $data);
    }

    public function busqueda()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $dia = $_POST["dia"] ?? "";
        $origen = $_POST["origen"] ?? "";

        if ($dia || $origen) {
            $respuesta = $this->tourModel->getTours($dia, $origen);

            if ($respuesta) {
                $data["tours"] = $respuesta;
            } else {
                $data["sinDatosTours"] = "Ups! No hay vuelos para el dia y origen seleccionado!";
            }

        } else {
            $data["errorSinSeleccion"] = "Error! Debe seleccionar al menos un dia o un origen";
        }

        $this->printer->generateView('tourView.html', $data);
    }

    public function busquedaTodosLosTours()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $respuesta = $this->tourModel->getTodosLosTours();

        if ($respuesta) {
            $data["tours"] = $respuesta;
        } else {
            $data["sinDatosTours"] = "Ups! No hay vuelos!";
        }


        $this->printer->generateView('tourView.html', $data);
    }


    public function confirmarVueloTour()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
        }

        $id = $_GET["id"] ?? "";

        $planificacion = $this->tourModel->getPlanificacion($id);
        $datosModelo = $this->tourModel->getDatosModelo($id);

        $data["id"] = $id;
        $data["planificacion"] = $planificacion;
        $data["datosModelo"] = $datosModelo;

        $this->printer->generateView('confirmarVueloTour.html', $data);
    }

    public function generarPDFyQR()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
        }

        $id = $_GET["id"] ?? "";

        $planificacion = $this->tourModel->getPlanificacion($id);
        $datosModelo = $this->tourModel->getDatosModelo($id);

        $data["id"] = $id;
        $data["planificacion"] = $planificacion;
        $data["datosModelo"] = $datosModelo;

        //$this->printer->generateView('confirmarVueloTour.html', $data);


// instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $html = 'hola.html';

        $dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser
        $dompdf->stream("ReservaVueloTour.pdf", ['Attachment' => 0]);


    }


}