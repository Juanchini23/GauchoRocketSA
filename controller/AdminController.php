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

        $tOcupacionPorViajeCircuitoDos = $this->adminModel->getTOcupacionPorviaje(3, 'circuitoDos');
        $data["circuitoDos"] = $tOcupacionPorViajeCircuitoDos[0]['circuitoDos'];

//        /Tasa de ocupacion por tipo viaje


        $this->printer->generateView('adminView.html', $data);
    }
}