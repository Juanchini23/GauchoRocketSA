<?php

class HomeController
{
    private $printer;

    public function __construct($homeModel, $printer)
    {
        $this->printer = $printer;
        $this->homeModel = $homeModel;
    }

    public function execute()
    {
        if (isset($_SESSION["AdminIn"]) || isset($_SESSION["ClienIn"])) {
            $respuesta["loggeado"] = 1;
            $respuesta["nombre"] = $this->homeModel->solicitarNombreUsuario();
        } else
            $respuesta = false;
        $this->printer->generateView('homeView.html', $respuesta);
    }


    public function logout()
    {
        session_encode();
        session_destroy();
        $this->printer->generateView('homeView.html');
    }

    public function registrarse()
    {

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $mail = $_POST["mail"];
        $clave = $_POST["clave"];
        $duplicado = $this->homeModel->estaDuplicado($mail);

        //si el mail ya existe en la base de datos no lo creo y paso mensaje de uuario existente
        if ($duplicado) {
            $respuesta["duplicado"] = "Usuario existente";
            $this->execute($respuesta);
        } //si el mail no existe crea un usuario nuevo correctamente
        else {
            $this->homeModel->registrarEnBd($nombre, $apellido, $mail, $clave);
            $respuesta["loggeado"] = $this->homeModel->isUser($nombre, $clave);
            $respuesta["nombre"] = $nombre;
            $this->execute($respuesta);
        }

    }
}