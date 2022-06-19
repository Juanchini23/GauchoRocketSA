<?php

class RegistrarController
{

    private $printer;
    private $registrarModel;
    private $loginModel;

    public function __construct($registrarModel, $loginModel, $printer)
    {
        $this->printer = $printer;
        $this->registrarModel = $registrarModel;
        $this->loginModel = $loginModel;
    }

    public function execute()
    {
        $data = Validator::validarSesion();

        $this->printer->generateView('homeView.html', $data);
    }

    public function registrarse()
    {

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $mail = $_POST["mail"];
        $clave = md5($_POST["clave"]);
        $centro = $_POST["centro"];
        $duplicado = $this->registrarModel->estaDuplicado($mail);

        //si el mail ya existe en la base de datos no lo creo y paso mensaje de usuario existente
        if ($duplicado) {
            $data["duplicado"] = "Ya existe un usuario con ese mail";
            $this->printer->generateView('homeView.html', $data);
        }
        else {       //si el mail no existe crea un usuario nuevo correctamente
            $this->registrarModel->registrarEnBd($nombre, $apellido, $mail, $clave, $centro);
            $data["loggeado"] = $this->loginModel->isUser($nombre, $clave);
            $data["nombre"] = $nombre;
            $this->printer->generateView('homeView.html', $data);
        }

    }

}