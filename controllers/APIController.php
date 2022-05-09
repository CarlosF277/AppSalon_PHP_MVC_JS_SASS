<?php

namespace Controllers;

use Model\Servicio;

class APIController{
    public static function index(){
        
        $servicios = Servicio::all();

        //convertir a JSON
        echo json_encode($servicios);
        
        //debuguear($servicios);
    }
}