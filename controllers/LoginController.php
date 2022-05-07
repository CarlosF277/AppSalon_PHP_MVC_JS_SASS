<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {
    public static function login(Router $router){

        $alertas = [];
        $auth = new Usuario;

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $auth = new Usuario($_POST); //instancia para validacion de dtos

            //validacion de que se ingrese correo y contraseña
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //comprobar que exista el usuario
                $usuario = Usuario::where("email", $auth->email); //nstancia traida del usuario si existe su correo

                //Si existe el usuario
                if($usuario){
                    //verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autenticar al usuario
                        
                        session_start();

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        //Redireccionamiento. depende si el usuario es admin o no
                        if($usuario->admin === "1"){
                            //debuguear("es admin");
                            $_SESSION["admin"] = $usuario->admin ?? null;
                            header("Location: /admin");

                        }
                        else{
                            header("Location: /cita");
                        }
                    }
                }
                else{
                    Usuario::setAlerta("error", "usuario no encontrado");
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render("auth/login",[
            "alertas" => $alertas,
            "auth" => $auth
        ]);

    }

    public static function logout(){
        echo "Desde logout";
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();


            if(empty($alertas)){

                $usuario = Usuario::where("email", $auth->email);
                
                if($usuario && $usuario->confirmado === "1"){

                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    //debuguear($usuario);

                    //todo enviar el email
                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //alerta de exito
                    Usuario::setAlerta("exito", "Revisa tu email");

                }
                else{
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
                    $alertas = Usuario::getAlertas();
                }
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render("auth/olvide-password",[
            "alertas" => $alertas

        ]);
    }

    public static function recuperar (Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET["token"]);

        //buscar usuario por su token
        $usuario = Usuario::where("token", $token);

        if(empty($usuario)){
            Usuario::setAlerta("error", "token no valido");
            $error = true;  
        }

        //recuperar con post
        if ($_SERVER["REQUEST_METHOD"] === "POST"){
            //Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header("Location: /");
                }

            }

        }


        $alertas = Usuario::getAlertas();
        $router->render("auth/recuperar-password",[
            "alertas" => $alertas,
            "error" => $error
        ]);
    }

    public static function crear (Router $router){

        //instanciar usuario
        $usuario = new Usuario; //para poder guardar los valores en el formulario en caso de que haya un error
        //y no genere errores de que no existen variables al momento de abrir el formulario

        //alertas vacias
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas este vacio
            if(empty($alertas)){
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado ->num_rows){
                    $alertas = Usuario::getAlertas();
                    
                }
                else{
                    //No esta registrado
                    //hashear el password
                    $usuario->hashPassword();
                    
                    //Generar un token unico
                    $usuario->crearToken();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    //debuguear($email);

                    //Crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header("Location: /mensaje");
                    }

                    $email->enviarConfirmacion();
                }
                
            }
            //debuguear($alertas);
        }
        $router->render("auth/crear-cuenta",[
            "usuario" => $usuario,
            "alertas" =>$alertas

        ]);
    }

    public static function mensaje (Router $router){

        $router->render("auth/mensaje");
    }

    public static function confirmar(Router $router){

        $alertas = [];
        //get para leer el token de la url
        $token = s($_GET["token"]);
        $usuario = Usuario::where("token", $token);

        if(empty($usuario)){
            //Mosttar mensaje de error
            Usuario::setAlerta("error", "Token no valido");

        }
        else{

            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();
            
            Usuario::setAlerta("exito", "Cuenta comprobada correctamente");

        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar-cuenta",[
            "alertas"=>$alertas
        ]);
    }
}