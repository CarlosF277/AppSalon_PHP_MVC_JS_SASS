<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {
    public static function login(Router $router){
        
        $router->render("auth/login");

    }

    public static function logout(){
        echo "Desde logout";
    }

    public static function olvide(Router $router){
        $router->render("auth/olvide-password",[

        ]);
    }

    public static function recuperar (){
        echo "desde recuperar";
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