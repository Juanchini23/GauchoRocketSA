<?php

class OrbitalController
{

    private $orbitalModel;
    private $printer;

    public function __construct($orbitalModel, $printer)
    {
        $this->orbitalModel = $orbitalModel;
        $this->printer = $printer;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

        $this->printer->generateView('orbitalView.html', $data);
    }

    public function busqueda()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $_SESSION["origen"] = $_POST["origen"] ?? "";
        $_SESSION["fecha"] = $_POST["fecha"] ?? "";

        $dia = date('l', strtotime($_SESSION["fecha"]));
        $codigoviajero = $_SESSION["codigoViajero"] ?? "";
        $respuesta = $this->orbitalModel->getOrbitales($dia, $_SESSION["origen"], $codigoviajero);

        if($respuesta){
            $data["orbitales"] = $respuesta;
        } else{
            $data["sinDatosOrbitales"] = "Error! Debe seleccionar al menos un dia o un origen";
        }

        $this->printer->generateView('orbitalView.html', $data);
    }

}