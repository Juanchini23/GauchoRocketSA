<?php

class ReservaController
{

    private $reservaModel;
    private $printer;

    public function __construct($reservaModel, $getPrinter)
    {
        $this->reservaModel = $reservaModel;
        $this->printer = $getPrinter;
    }

    public function execute()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $id = $_GET["id"] ?? "";
        $fechaViaje = $_GET["fechaviaje"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $this->reservaModel->guardarViajeFecha($idUser, $id, $fechaViaje);
        //$respuesta = $this->homeModel->getEspecificacion($id);
//        $data["especifiacion"] = $respuesta;
        $data [] = "";

        $this->printer->generateView('reservaView.html', $data);
    }
}