<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuacion</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return null?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
    </div>

    <input type="submit" class="boton" value="Guardar nueva contraseña">

</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
    <a href="/">Ya tienes una cuenta? Inicia sesion</a>
</div>