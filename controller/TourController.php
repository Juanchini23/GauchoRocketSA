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

    public function execute($data =[])
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }

        $this->printer->generateView('tourView.html', $data);
    }
}