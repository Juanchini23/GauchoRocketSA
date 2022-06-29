<?php

class HomeController
{
    private $printer;

    private $circuitoUnoBA = array(["Tierra" => 0, "EEI" => 4, "HotelOrbital" => 8, "Luna" => 16, "Marte" => 26]);
    private $circuitoUnoAA = array(["Tierra" => 0, "EEI" => 3, "HotelOrbital" => 6, "Luna" => 9, "Marte" => 22]);
    private $circuitoDosBA = array(["Tierra" => 0, "EEI" => 4, "Luna" => 14, "Marte" => 26, "Ganimedes" => 48, "Europa" => 50, "Io" => 51, "Encedalo" => 70, "Titan" => 77]);
    private $circuitoDosAA = array(["Tierra" => 0, "EEI" => 3, "Luna" => 10, "Marte" => 22, "Ganimedes" => 32, "Europa" => 33, "Io" => 35, "Encedalo" => 50, "Titan" => 52]);


    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute()
    {
        $data = Validator::validarSesion();
        $codigoviajero = $_SESSION["codigoViajero"] ?? "";
        if (isset($_SESSION["origen"]) && isset($_SESSION["fecha"]) && isset($_SESSION["destino"])) {
            $dia = date('l', strtotime($_SESSION["fecha"]));
            $localStorage = $this->homeModel->busquedaVuelos($_SESSION["origen"], $dia, $codigoviajero);
            $data["planificacion"] = $localStorage;
        }
        $_SESSION['errorNoHayAciento'] = 0;
        $this->printer->generateView('homeView.html', $data);
    }

    public function busqueda()
    {
        $data = Validator::validarSesion();

        $origen = $_POST["origen"] ?? "";
        $fecha = $_POST["fecha"] ?? "";
        $destino = $_POST["destinoVuelo"] ?? "";

        // LocalStorage
        $_SESSION["origen"] = $origen;
        $_SESSION["fecha"] = $fecha;
        $_SESSION["destino"] = $destino;
        // /LocalStorage


        $codigoviajero = $_SESSION["codigoViajero"];

        // como saber el dia de la semana que es la fecha que nos llega desde el formulario de entredestinos
        $dia = date('l', strtotime($fecha));

        // si viene destino diferente a BA tiene que buscar planificaciones que segun la suma de la hora de salida
        // mas lo que tarda en llegar ahi matcheen bien con el dia de salida de la planificacion
        // hacer query en base a una query anterior para agarrar la query con la hora correcta
        // hacer calculos de horaa y dia en php y no js

        if ($destino == 'BA' || $destino == 'AK') {
            //ver si el destino que ponemos pasa por donde debe
            $respuesta = $this->homeModel->busquedaVuelos($origen, $dia, $codigoviajero, $destino);
            $data["planificacion"] = $respuesta;
        } else {
            //calculos de hora y dia de llegada + query de busqueda

            $respuesta = $this->homeModel->busquedaVuelosOrigen($origen, $dia, $codigoviajero);
            $data["planificacion"] = $respuesta;
        }


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

}