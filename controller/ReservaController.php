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
        $data = Validator::validarSesion();

        $id = $_GET["id"] ?? "";
        $fechaViaje = $_GET["fechaviaje"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido

        $planificacion = $this->reservaModel->getPlanificacion($id);

        $data["planificacion"] = $planificacion;
        $data["fechaSalida"] = $fechaViaje;

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservar()
    {

        //$this->reservaModel->guardarViajeFecha($idUser, $id, $fechaViaje);
    }
}