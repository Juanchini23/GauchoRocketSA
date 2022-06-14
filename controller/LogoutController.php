<?php

class LogoutController
{
    private $printer;

    public function __construct($printer)
    {
        $this->printer = $printer;
    }

    public function execute(){
        session_encode();
        session_destroy();
        header("location: /");
		exit();
    }

}