<h1 class="nombre-pagina">Olvide contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu E-Mail a continuacion</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">Tu E-Mail</label>
        <input type="email" id="email" name="email" placeholder="Tu E-mail">
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
    <a href="/">Ya tienes una cuenta? Inicia sesion</a>
</div>