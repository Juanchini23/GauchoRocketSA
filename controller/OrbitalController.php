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

        $dia = $_POST["dia"] ?? "";
        $origen = $_POST["origen"] ?? "";

        $respuesta = $this->orbitalModel->getOrbitales($dia, $origen);

        if($respuesta){
            $data["orbitales"] = $respuesta;
        } else{
            $data["sinDatosOrbitales"] = "Error! Debe seleccionar al menos un dia o un origen";
        }

        $this->printer->generateView('orbitalView.html', $data);
    }

    public function busquedaTodosLosOrbitales()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $respuesta = $this->orbitalModel->getTodosLosOrbitales();
        $data["orbitales"] = $respuesta;

        $this->printer->generateView('orbitalView.html', $data);
    }

}