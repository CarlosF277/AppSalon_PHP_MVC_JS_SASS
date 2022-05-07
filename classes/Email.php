<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
        
    }

    public function enviarConfirmacion(){

        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "smtp.mailtrap.io";
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = "82b0e1f14f4acb";
        $mail->Password = "3fab2cfa0e11d3";

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("cuentas@appsalon.com", "appsalon.com");
        $mail->Subject = "Confirma tu cuenta";

        //set html
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola ".$this->nombre ."</strong> Has
        creado tu cuenta en Appsalon. Solo debes confirmarla presionando
        en el siguiente enlace: </p>"; 
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confirmar cuenta</a> </p>";
        $contenido .= "<p>Si no creaste una cuenta, ignora este correo </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviar el email
        $mail->send();

    }

    public function enviarInstrucciones(){

                //crear el objeto de email
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = "smtp.mailtrap.io";
                $mail->SMTPAuth = true;
                $mail->Port = 2525;
                $mail->Username = "82b0e1f14f4acb";
                $mail->Password = "3fab2cfa0e11d3";
        
                $mail->setFrom("cuentas@appsalon.com");
                $mail->addAddress("cuentas@appsalon.com", "appsalon.com");
                $mail->Subject = "Reestablece tu contraseña";
        
                //set html
                $mail->isHTML(TRUE);
                $mail->CharSet = "UTF-8";
        
                $contenido = "<html>";
                $contenido .= "<p><strong> Hola ".$this->nombre ."</strong> Has
                solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo </p>"; 
                $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/recuperar?token=".$this->token."'>Reestablecer contraseña</a> </p>";
                $contenido .= "<p>Si no creaste una cuenta, ignora este correo </p>";
                $contenido .= "</html>";
        
                $mail->Body = $contenido;
        
                //enviar el email
                $mail->send();

    }
}