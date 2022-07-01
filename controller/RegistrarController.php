<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once 'third-party/phpmailer/Exception.php';
require_once 'third-party/phpmailer/PHPMailer.php';
require_once 'third-party/phpmailer/SMTP.php';

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
        $codigo = rand(1000,9999);
        //si el mail ya existe en la base de datos no lo creo y paso mensaje de usuario existente
        if ($duplicado) {
            $data["duplicado"] = "Ya existe un usuario con ese mail";
            $this->printer->generateView('homeView.html', $data);
        } else {       //si el mail no existe crea un usuario nuevo correctamente
           $this->registrarModel->registrarEnBd($nombre, $apellido, $mailUsuario, $clave, $centro, $estado, $codigo);

            //$data["loggeado"] = $this->loginModel->isUser($nombre, $clave);
            //$data["nombre"] = $nombre;
            //$this->printer->generateView('homeView.html', $data);


            //Crear una instancia de PHPMailer
            $mail = new PHPMailer();
            //Definir que vamos a usar SMTP
            $mail->IsSMTP();
            //Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
            // 0 = off (producción)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ahora definimos gmail como servidor que aloja nuestro SMTP
            $mail->Host = 'smtp.gmail.com';
            //El puerto será el 587 ya que usamos encriptación TLS
            $mail->Port = 587;
            //Definmos la seguridad como TLS
            $mail->SMTPSecure = 'tls';
            //Tenemos que usar gmail autenticados, así que esto a TRUE
            $mail->SMTPAuth = true;
            //Definimos la cuenta que vamos a usar. Dirección completa de la misma
            $mail->Username = "gauchorocket30@gmail.com";
            //Introducimos nuestra contraseña de gmail
            $mail->Password = "ghufmaqyoyoroiju";
            //Definimos el remitente (dirección y, opcionalmente, nombre)
            $mail->SetFrom('gauchorocket30@gmail.com', 'GauchoRocket');
            //Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
            $mail->AddAddress($mailUsuario, 'El Destinatario');
            //Definimos el tema del email
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Active su cuenta de GauchoRocketSA!';
            //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
            //$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
            //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
            $mail->Body = 'Codigo de validacion:'.$codigo . '<br><br> Ingrese al siguiente link para para terminar el proceso de validacion: <a href="http://localhost/registrar/redireccionarMail">Link</a>';
            $mail->AltBody = 'This is a plain-text message body';
            //Enviamos el correo
            if (!$mail->Send()) {
                echo "Error: " . $mail->ErrorInfo;
            }


            $this->printer->generateView('validacion.html');

        }

    }


    public function validacion()
    {

        $codigoValidar = $_POST["codigoValidacion"] ?? "";

        $respuesta = $this->registrarModel->existeCodigo($codigoValidar);
var_dump($respuesta);
        if($respuesta){
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