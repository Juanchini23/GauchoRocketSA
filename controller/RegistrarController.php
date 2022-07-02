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

        $nombre = $_POST["nombre"] ?? "";
        $apellido = $_POST["apellido"] ?? "";
        $mailUsuario = $_POST["mail"] ?? "";
        $clave = md5($_POST["clave"]) ?? "";
        $centro = $_POST["centro"] ?? "";
        $duplicado = $this->registrarModel->estaDuplicado($mailUsuario);
        $estado = 0;
        $codigo = rand(1000, 9999);
        //si el mail ya existe en la base de datos no lo creo y paso mensaje de usuario existente
        if ($duplicado) {
            $data["duplicado"] = "Ya existe un usuario con ese mail";
            $this->printer->generateView('homeView.html', $data);
        } else {       //si el mail no existe crea un usuario nuevo correctamente
            $this->registrarModel->registrarEnBd($nombre, $apellido, $mailUsuario, $clave, $centro, $estado, $codigo);

            //$data["loggeado"] = $this->loginModel->isUser($nombre, $clave);
            //$data["nombre"] = $nombre;
            //$this->printer->generateView('homeView.html', $data);

            Validator::enviarMail($mailUsuario,
                'Active su cuenta de GauchoRocketSA!',
                'Codigo de validacion:' . $codigo . '<br><br> Ingrese al siguiente link para para terminar el proceso de validacion: <a href="http://localhost/registrar/redireccionarMail">Link</a>');

            $this->printer->generateView('validacion.html');
        }
    }


    public function validacion()
    {

        $codigoValidar = $_POST["codigoValidacion"] ?? "";

        $respuesta = $this->registrarModel->existeCodigo($codigoValidar);
        var_dump($respuesta);
        if ($respuesta) {
            $this->registrarModel->activarUsuario($codigoValidar);
        }
        header("location: /");
        exit();

    }

    public function redireccionarMail()
    {

        $this->printer->generateView('validacion.html');
    }

}