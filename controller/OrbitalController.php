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
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
        }
        $this->printer->generateView('orbitalView.html', $data = []);
    }
}