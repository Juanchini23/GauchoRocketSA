<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute($respuesta = [])
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $respuesta["loggeado"] = 1;
            $respuesta["nombre"] = $_SESSION["usuario"];
        }
        $this->printer->generateView('homeView.html', $respuesta);
    }

    public function busqueda()
    {
        $origen = $_POST["origen"] ?? "";

        $respuesta = $this->homeModel->busquedaVuelos($origen);
        $data["planificacion"] = $respuesta;

        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $this->printer->generateView('homeView.html', $data);
    }

}