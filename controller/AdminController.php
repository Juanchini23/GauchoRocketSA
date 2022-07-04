<?php

class AdminController
{

    private $printer;

    public function __construct($adminModel, $printer)
    {
        $this->printer = $printer;
        $this->adminModel = $adminModel;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

//        Tasa de ocupacion por tipo viaje

        $tOcupacionPorViajeOrbital = $this->adminModel->getTOcupacionPorviaje(1, 'orbitales');
        $data["orbitales"] = $tOcupacionPorViajeOrbital[0]['orbitales'];

        $tOcupacionPorViajeTour = $this->adminModel->getTOcupacionPorviaje(2, 'tour');
        $data["tour"] = $tOcupacionPorViajeTour[0]['tour'];

        $tOcupacionPorViajeCircuitoUno = $this->adminModel->getTOcupacionPorviaje(3, 'circuitoUno');
        $data["circuitoUno"] = $tOcupacionPorViajeCircuitoUno[0]['circuitoUno'];

        $tOcupacionPorViajeCircuitoDos = $this->adminModel->getTOcupacionPorviaje(4, 'circuitoDos');
        $data["circuitoDos"] = $tOcupacionPorViajeCircuitoDos[0]['circuitoDos'];

//        /Tasa de ocupacion por tipo viaje

//        Tasa de ocupacion por equipo

        $tOcupacionPorTipoViajeOrbital = $this->adminModel->getTOcupacionPorTipoCiaje('OR', 'orbitalesOr');
        $data["orbitalesOr"] = $tOcupacionPorTipoViajeOrbital[0]["orbitalesOr"];

        $tOcupacionPorTipoViajeBajaAceleracion = $this->adminModel->getTOcupacionPorTipoCiaje('BA', 'bajaAceleracion');
        $data["bajaAceleracion"] = $tOcupacionPorTipoViajeBajaAceleracion[0]["bajaAceleracion"];

        $tOcupacionPorTipoViajeAltaAceleracion = $this->adminModel->getTOcupacionPorTipoCiaje('AA', 'altaAceleracion');
        $data["altaAceleracion"] = $tOcupacionPorTipoViajeAltaAceleracion[0]["altaAceleracion"];

//        /Tasa de ocupacion por equipo

//        Cabinas
        $CantCabinaTurista = $this->adminModel->getCabinaTurita('turista');
        $data["cabinaTurista"] = $CantCabinaTurista[0]["turista"];

        $CantCabinaEjecutivo = $this->adminModel->getCabinaTurita('ejecutivo');
        $data["cabinaEjecutiva"] = $CantCabinaEjecutivo[0]["ejecutivo"];

        $CantCabinaPrimera = $this->adminModel->getCabinaTurita('primera');
        $data["cabinaPrimera"] = $CantCabinaPrimera[0]["primera"];
//        /Cabinas

//        Facturacion mensual

        $mesActual = $this->adminModel->getMesActual();
        $data["mesActual"] = $mesActual;
        $facturacionMensual = $this->adminModel->getFacturacionMensual();
        $data["facturacionMensual"] = $facturacionMensual[0]["facturacionMensual"];

//        /Facturacion mensual


        $this->printer->generateView('adminView.html', $data);
    }
}