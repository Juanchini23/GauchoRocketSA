<?php

class HomeModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getVuelos(){

    }

    private function login($usuario, $clave){
        return $this->database->query("SELECT nombre, clave, idRol FROM usuario WHERE nombre = '$usuario' AND clave = '$clave'");
    }

    public function isUser($user, $clave){
        $usuarios = $this->login($user, $clave);
        foreach ($usuarios as $usuario) {
            if ($usuario["nombre"] == $user && $usuario["clave"] == $clave) {
                if ($usuario["idRol"] == 1) {
				$_SESSION["adminIn"] = 1;
                    return "Admin" ;
                }
                if ($usuario["idRol"] == 2) {
                    $_SESSION["ClienIn"] = 2;
                    return  "Clien";
                }
            }
        }
    }

    //siempre registra un usuario del tipo cliente
    public function registrarEnBd($nombre, $apellido, $mail, $clave){

        $this->database->queryAltaUsuario("INSERT INTO usuario(idRol, nombre, apellido, mail, clave)
                                            values (2, '$nombre', '$apellido', '$mail', '$clave');");
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

