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

        <h1>Felicitaciones <span><?php echo $_SESSION["apellido"] . ", " .$_SESSION["usuario"] ;?></span>!!!</h1>
        <h3>Datos:</h3>
        <h5><?php echo $_SESSION["apellido"] . ", " .$_SESSION["usuario"] ;?></h5>
        <h5>Te vas el dia:<?php echo " " . $_SESSION["apellido"] . ", " .$_SESSION["usuario"] ;?></h5>
        <h5>Volves el dia:<?php echo " " . $_SESSION["apellido"] . ", " .$_SESSION["usuario"] ;?></h5>

        <p>A preparar las valijas!</p>
        <p>Buen viaje te desea tu compania amiga <strong>GAUCHO ROCKET!</strong></p>

        <?php
        echo $id;

        foreach ($planificacion as $row) {
            echo $row['modelo'];
        }
        echo "<br>";

        echo strtotime("now"), "\n";
        echo strtotime("10 September 2000"), "\n";
        echo strtotime("+1 day"), "\n";
        echo "<br>";
        echo date("jS F, Y", strtotime("now"));

        echo "<br>";
        echo date("jS F, Y", strtotime("+35 day"));


        echo "<br>";
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        echo $dias[date('w', strtotime("10 September 2000",1))]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;

        echo "<br>";
        //dia que se genera el PDF
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        echo "Archivo generado el: " . date("d-m-Y h:i:sa");

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






