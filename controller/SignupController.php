<?php
/* datos del usuario: rol = 'cliente', nombre, apellido, mail, clave*/
class Signup extends Controller
{
    public function __construct($printer)
    {
        parent::__construct($printer);
    }

    public function register()
    {
        $nombre = $this->post('nombre');
        $apellido = $this->post('apellido');
        $mail = $this->post('mail');
        $clave = $this->post('clave');

        if (
            !is_null($nombre)
            && !is_null($apellido)
            && !is_null($mail)
            && !is_null($clave)
        ) {
            $this->model->signup($nombre, $apellido, $mail, $clave);
            $usuarioNuevo = new Usuario($nombre, $apellido, $mail, $clave);
            $user->save();
            $this->$printer->render('loginView.html');
        } else {
            $this->render('error.html');
        }
    }
}
