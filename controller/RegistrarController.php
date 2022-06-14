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

    public function execute($data = [])
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
            $respuesta["duplicado"] = "Ya existe un usuario con ese mail";
            $this->execute($respuesta); // donde meto la respuesta?
        } //si el mail no existe crea un usuario nuevo correctamente
        else {
            $this->registrarModel->registrarEnBd($nombre, $apellido, $mail, $clave, $centro);
            $respuesta["loggeado"] = $this->loginModel->isUser($nombre, $clave);
            $respuesta["nombre"] = $nombre;
            $this->execute($respuesta); // donde meto la respuesta?
        }

    }

}