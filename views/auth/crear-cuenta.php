<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario prar crear una cuenta</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/crear-cuenta">

<div class="campo">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo s($usuario->nombre); ?>">
</div>

<div class="campo">
    <label for="apellido">Apellido</label>
    <input type="text" id="apellido" name="apellido" placeholder="Tu apelllido" value="<?php echo s($usuario->apellido); ?>">
</div>

<div class="campo">
    <label for="telefono">Telefono</label>
    <input type="tel" id="telefono" name="telefono" placeholder="Tu telefono" value="<?php echo s($usuario->telefono); ?>">
</div>


<div class="campo">
    <label for="email">E-Mail</label>
    <input type="email" id="email" name="email" placeholder="Tu E-Mail" value="<?php echo s($usuario->email); ?>">
</div>


<div class="campo">
    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" placeholder="Tu contraseña" >
</div>

<input type="submit" value="Crear cuenta" class="boton">

</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia sesion</a>
    <a href="/olvide">Olvidaste tu contraseña?</a>
</div>