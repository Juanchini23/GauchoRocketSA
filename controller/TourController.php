<?php

class TourController
{

    private $tourModel;
    private $printer;

    public function __construct($tourModel, $printer)
    {
        $this->tourModel = $tourModel;
        $this->printer = $printer;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

        $this->printer->generateView('tourView.html', $data);
    }

    public function busqueda()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $dia = $_POST["dia"] ?? "";
        $origen = $_POST["origen"] ?? "";

        $respuesta = $this->tourModel->getTours($dia, $origen);

        if($respuesta){
            $data["tours"] = $respuesta;
        } else{
            $data["sinDatosTours"] = "Error! Debe seleccionar al menos un dia o un origen";
        }


        $this->printer->generateView('tourView.html', $data);
    }

    public function busquedaTodosLosTours()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $respuesta = $this->tourModel->getTodosLosTours();
        $data["tours"] = $respuesta;

        $this->printer->generateView('tourView.html', $data);
    }


}