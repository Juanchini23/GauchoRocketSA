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
        $fechaViaje = $_GET["fechavuelo"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $hora = $_GET["hora"] ?? "";

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido

        $planificacion = $this->reservaModel->getPlanificacion($id);
        $datosModelo = $this->reservaModel->getDatosModelo($id);

        $data["idPlanificacion"] = $id;
        $data["datosModelo"] = $datosModelo;
        $data["planificacion"] = $planificacion;
        $data["fechaSalida"] = $fechaViaje;

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservar()
    {
        $origen = $_POST["origen"] ?? "";
        $destino = $_POST["destino"] ?? "";
        $diaSalida = $_POST["diaSalida"] ?? "";
        $horaSalida = $_POST["horaSalida"] ?? "";
        $butaca = $_POST["butaca"] ?? "";
        $cantidadAsientos = $_POST["cantidadAsientos"] ?? "";
        $metodoPago = $_POST["metodoPago"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $idPlanificacion = $_POST["idPlanificacion"] ?? "";
        $fechaSalida = $_POST["fechaSalida"] ?? "";
        // Generar una reserva
        $reservaExitosa = $this->reservaModel->generarReserva($origen, $destino, $diaSalida, $horaSalida, $butaca, $cantidadAsientos, $metodoPago, $idUser, $idPlanificacion, $fechaSalida);
    }
}