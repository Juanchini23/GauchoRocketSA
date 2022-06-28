<?php

class AdminController
{

    private $printer;

    public function __construct($adminModel, $printer)
    {
        $this->printer = $printer;
        $this->adminModel = $adminModel;
    }

    public function execute(){
        $data = Validator::validarSesion();

        $this->printer->generateView('adminView.html', $data);
    }
}