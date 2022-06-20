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
        $hora = $_GET["hora"] ?? "";

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido

        $planificacion = $this->reservaModel->getPlanificacion($id);
        $datosModelo = $this->reservaModel->getDatosModelo($id);
        $datosLLegada = $this->reservaModel->getDatosLlegada($id, $fechaViaje);

        $data["idPlanificacion"] = $id;
        $data["datosModelo"] = $datosModelo;
        $data["planificacion"] = $planificacion;
        $data["fechaSalida"] = $fechaViaje;
        $data["datosLlegada"] = $datosLLegada;

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservar()
    {
        $destino = $_POST["destino"] ?? "";
        $butaca = $_POST["butaca"] ?? "";
        $cantidadAsientos = $_POST["cantidadAsientos"] ?? "";
        $metodoPago = $_POST["metodoPago"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $idPlanificacion = $_POST["idPlanificacion"] ?? "";
        $fechaViaje = $_POST["dia"] ?? "";
        $horaLlegada = $_POST["llegadaHora"] ?? "";

        var_dump($destino, $butaca, $cantidadAsientos, $metodoPago, $idPlanificacion, $idUser, $fechaViaje, $horaLlegada);


        // Generar una reserva
        //$this->reservaModel->guardarViajeFecha($idUser, $id, $fechaViaje);
    }
}