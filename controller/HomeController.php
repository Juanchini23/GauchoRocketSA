<?php

class HomeController
{
    private $printer;

    private $circuitoUnoBA = array("tierra" => 0, "eei" => 4, "orbitalHotel" => 8, "luna" => 16, "marte" => 26);
    private $circuitoUnoAA = array("tierra" => 0, "eei" => 3, "orbitalHotel" => 6, "luna" => 9, "marte" => 22);


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
        $origen = $_POST["origen"] ?? "";
        $fecha = $_POST["fecha"] ?? "";
        $destino = $_POST["destino"] ?? "";

        // LocalStorage
        $_SESSION["origen"] = $origen;
        $_SESSION["fecha"] = $fecha;
        $_SESSION["destino"] = $destino;

        $codigoviajero = $_SESSION["codigoViajero"];

        // como saber el dia de la semana que es la fecha que nos llega desde el formulario de entredestinos
        $dia = date('l', strtotime($fecha));
        $respuesta = $this->homeModel->busquedaVuelos($origen, $dia, $codigoviajero);
        $data["planificacion"] = $respuesta;

        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
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

    public function datos(){
        $data = Validator::validarSesion();

        $this->printer->generateView('adminView.html', $data);
    }

}