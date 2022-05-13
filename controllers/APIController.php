<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        
        $servicios = Servicio::all();

        //convertir a JSON
        echo json_encode($servicios);
        
        //debuguear($servicios);
    }
    
    public static function guardar(){
        /*
        $respuesta = [
            "datos" => $_POST
        ];
        echo json_encode($respuesta);*/

        //Almacena la cita y devuelve el id
        
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado["id"];


        //Almacena la cita y los servicios con el id de la cita
        //separando los ids de servicios para que sea un array
        $idServicios = explode(",", $_POST["servicios"]);

        foreach($idServicios as $idServicio){
            $args = [
                "citaId" => $id,
                "servicioId" => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        //retornamos una respuesta
        $respuesta = [
            "resultado" =>  $resultado
        ];



        echo json_encode($respuesta);
    }

    
}