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

        if (isset($_SESSION["origen"]) && isset($_SESSION["fecha"]) && isset($_SESSION["destino"])) {
            $dia = date('l', strtotime($_SESSION["fecha"]));
            $localStorage = $this->homeModel->busquedaVuelos($_SESSION["origen"], $dia);
            $data["planificacion"] = $localStorage;
        }

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

        // como saber el dia de la semana que es la fecha que nos llega desde el formulario de entredestinos
        $dia = date('l', strtotime($fecha));
        $respuesta = $this->homeModel->busquedaVuelos($origen, $dia);
        $data["planificacion"] = $respuesta;

        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }
        $data["fecha"] = $fecha;
        $data["destino"] = $destino;
        $this->printer->generateView('homeView.html', $data);
    }

}