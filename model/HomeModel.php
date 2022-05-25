<?php

class HomeModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getVuelos()
    {

    }

    public function solicitarNombreUsuario()
    {
        $usurios = $this->database->query("SELECT * FROM usuarioLogeado");
        foreach ($usurios as $usurio){
            return $usurio["nombre"];
        }
    }


    //siempre registra un usuario del tipo cliente
    public function registrarEnBd($nombre, $apellido, $mail, $clave)
    {

        $this->database->queryAltaUsuario("INSERT INTO usuario(idRol, nombre, apellido, mail, clave)
                                            values (2, '$nombre', '$apellido', '$mail', '$clave');");
    }


    //me devuelve un array con todos los mails de los usuarios
    public function consultaMailTodosLosUsuarios()
    {
        return $this->database->query("SELECT mail FROM usuario");
    }

    public function estaDuplicado($mail)
    {
        $duplicado = false;

        //consulto todos los mails y los guardo
        $todosLosMails = $this->consultaMailTodosLosUsuarios();

        //recorro todos los mails y me devuelve true o false si esta repetido
        foreach ($todosLosMails as $mails) {
            if ($mails["mail"] == $mail) {
                $duplicado = true;
                break;
            }
        }
        return $duplicado;
    }


}

