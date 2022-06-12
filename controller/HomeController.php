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
        $fecha = $_POST["fecha"] ?? "";

        // como saber el dia de la semana que es la fecha que nos llega desde el formulario de entredestinos
        $dia = date('l', strtotime($fecha));

        $respuesta = $this->homeModel->busquedaVuelos($origen,$dia);
        $data["planificacion"] = $respuesta;

        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }
        $data["fecha"]=$fecha;
        $this->printer->generateView('homeView.html', $data);
    }

    public function especifiacion()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $id = $_GET["id"];
        $fechaViaje= $_GET["fechaviaje"];
        $idUser = $_SESSION["idUserLog"];
        $this->homeModel->guardarViajeFecha($id, $fechaViaje, $idUser);
        //$respuesta = $this->homeModel->getEspecificacion($id);
        //$data["especifiacion"] = $respuesta;

        $this->printer->generateView('homeView.html', $data);
    }

}