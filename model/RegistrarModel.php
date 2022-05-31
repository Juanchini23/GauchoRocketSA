<?php

class RegistrarModel{

    private $database;

    public function __construct($database){

        $this->database = $database;
    }


    //siempre registra un usuario del tipo cliente
    public function registrarEnBd($nombre, $apellido, $mail, $clave, $centro){
        $codigoViajero = rand(1,3);
//        $this->database->restarCapacidadDiaria($centro);
        $this->database->queryAltaUsuario($nombre, $apellido, $mail, $clave, $codigoViajero);
    }


    //me devuelve un array con todos los mails de los usuarios
    public function consultaMailTodosLosUsuarios(){
        return $this->database->query("SELECT mail FROM usuario");
    }

    public function estaDuplicado($mail){
        $duplicado = false;

        //consulto todos los mails y los guardo
        $todosLosMails = $this->consultaMailTodosLosUsuarios();

        //recorro todos los mails y me devuelve true o false si esta repetido
        foreach ($todosLosMails as $mails){
            if ($mails["mail"] == $mail){
                $duplicado = true;
                break;
            }
        }
        return $duplicado;
    }
}