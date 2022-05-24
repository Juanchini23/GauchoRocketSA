<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute($respuesta = []){

        $this->printer->generateView('homeView.html', $respuesta);
    }

    public function login(){

        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];
        $respuesta["loggeado"] = $this->homeModel->isUser($usuario, $clave);
        $respuesta["nombre"] = $usuario;
        $this->execute($respuesta);
    }

    public function logout(){
        session_encode();
        session_destroy();
        $this->printer->generateView('homeView.html');
    }

    public function registrarse(){

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $mail = $_POST["mail"];
        $clave = $_POST["clave"];
        $this->homeModel->registrarEnBd($nombre, $apellido, $mail, $clave);
        $respuesta["loggeado"] = $this->homeModel->isUser($nombre, $clave);
        $respuesta["nombre"] = $nombre;
        $this->execute($respuesta);
    }
}