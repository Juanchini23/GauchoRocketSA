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
        $fechaViaje = $_SESSION["fecha"] ?? "";
        $idUser = $_SESSION["idUserLog"] ?? "";
        $hora = $_GET["hora"] ?? "";

        // planificacion: origen reserva diasalida horasalida
        // calculado a futuro: horallegada diallegada
        // usuario: nombre apellido
        if($this->reservaModel->isOrbital($id)){
            $data["orbital"] =1;
        }

        $planificacion = $this->reservaModel->getPlanificacion($id);
        $datosModelo = $this->reservaModel->getDatosModelo($id);
        $datosAsientos = $this->reservaModel->getCantidadAsientosReservados($id, $fechaViaje);

        $data["asientoTurista"] = $datosModelo[0]["turista"] - $datosAsientos[0]["turista"];
        $data["asientoEjecutivo"] = $datosModelo[0]["ejecutivo"] - $datosAsientos[0]["ejecutivo"];
        $data["asientoPrimera"] = $datosModelo[0]["primera"] - $datosAsientos[0]["primera"];
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

    public function reservarOrbital(){
        $idPlanificacion = $_GET["idPlanificacion"];
    }
}