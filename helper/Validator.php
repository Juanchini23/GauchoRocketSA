<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once 'third-party/phpmailer/Exception.php';
require_once 'third-party/phpmailer/PHPMailer.php';
require_once 'third-party/phpmailer/SMTP.php';

class Validator
{

    public static function validarSesion()
    {
        if (isset($_SESSION["ClienIn"]) || isset($_SESSION["AdminIn"])) {
            $data["loggeado"] = 1;
            $data["nombre"] = $_SESSION["usuario"];
            $data["apellido"] = $_SESSION["apellido"];
            if (isset($_SESSION["AdminIn"])) {
                $data["AdminIn"] = 2;
            }
            return $data;
        }
    }

    public static function generarCodigo($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    public static function enviarMail($mailUsuario, $mensaje, $body)
    {
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
        $mail->Subject = $mensaje;
        //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
        //$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
        //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
        $mail->Body = $body;
        $mail->AltBody = 'This is a plain-text message body';
        //Enviamos el correo
        if (!$mail->Send()) {
            echo "Error: " . $mail->ErrorInfo;
        }
    }
}