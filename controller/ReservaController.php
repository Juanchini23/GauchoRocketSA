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

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido

        $planificacion = $this->reservaModel->getPlanificacion($id);
        $usuario = $this->reservaModel->getUsuario($idUser);

        $data["planificacion"]= $planificacion;
        $data["usuario"]= $usuario;
        $data["fechaSalida"]= $fechaViaje;

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservar()
    {

//        $this->reservaModel->guardarViajeFecha($idUser, $id, $fechaViaje);
    }
}