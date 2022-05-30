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
            $respuesta["nombre"] = $this->homeModel->solicitarNombreUsuario();
        } else
            $respuesta = false;
        $this->printer->generateView('homeView.html', $respuesta);
    }

    public function reserva()
    {
        $tipoVuelo = $_POST["tipoVuelo"] ?? "";
        $origen = $_POST["origen"] ?? "";
        $destino = $_POST["destino"] ?? "";
        $salida = $_POST["salida"] ?? "";
        $vuelta = $_POST["vuelta"] ?? "";
        $personas = $_POST["personas"] ?? "";
        $clase = $_POST["clase"] ?? "";

        $respuesta = $this->homeModel->busquedaVuelos($origen, $destino, $salida, $vuelta);

        $this->execute($respuesta);
    }

}