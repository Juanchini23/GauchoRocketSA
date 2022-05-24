<?php

class Controller
{
    private $printer;

    public function __construct($printer)
    {
        $this->printer = $printer;
    }

    public function execute()
    {
        $this->printer->generateView('homeView.html');
    }

    protected function render($vista, $data = [])
    {
        $this->printer->generateView($vista, $data);
    }

    protected function post($parametro)
    {
        if (!isset($_POST[$parametro])) {
            return null;
        }
        return $_POST[$parametro];
    }

    protected function get($parametro)
    {
        if (!isset($_GET[$parametro])) {
            return null;
        }
        return $_GET[$parametro];
    }
}
