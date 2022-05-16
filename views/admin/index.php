<h1 class="nombre-pagina">Panel de administracion</h1>

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<h2>Buscar citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input id="fecha" type="date" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>

</div>

<?php
//mensaje por si no hay citas
    if(count($citas) === 0){
        echo "<h2>No hay citas en esta fecha</h2>";
    }

?>

<div id="citas-admin">

    <ul class="citas">
        <?php 
            $idCita = 0;
            foreach($citas as $key => $cita){ 

            //Comprobacion para evitar mostrar ids repetidos*
                 if($idCita !== $cita->id){ 

                    //para mostrar el total de cada cita
                     $total = 0;

                     //operador ternario para cerrar el li despues de la primera iteracion de datos
                     echo $idCita !== 0 ? "</li>" : "";
        ?>

        <li>
                <h3>Informacion</h3>

                <p> ID: <span> <?php echo $cita->id; ?></span> </p>
                <p> Hora: <span> <?php echo $cita->hora; ?></span> </p>
                <p> Cliente: <span> <?php echo $cita->cliente; ?></span> </p> 
                <p> Email: <span> <?php echo $cita->email; ?></span> </p>
                <p> Telefono: <span> <?php echo $cita->telefono; ?></span> </p>  

                <h3>Servicios</h3>

         <?php 

                 $idCita = $cita->id;
            
            } //fin de if 
                $total += $cita->precio;
         ?> 
                <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>

            <?php
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;

                if(esUltimo($actual, $proximo)){ ?>

                    <p class="total">Total: <span><?php echo $total; ?></span></p>

                    <form action="api/eliminar" method="POST"> <!--Form con input oculto para eliminar cita-->
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="eliminar">

                    </form>

            <?php   } //fin de id de esUltimo?>

        <!--</li> se quita para que html lo cierre solo y agregue los servicios dentro del li en las iteraciones. edit, se cierra en cada 
        iteracion del if con un echo,  excepto la primera -->

        <?php } //fin de foreach ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
              
?>