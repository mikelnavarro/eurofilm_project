<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOMBRESITIO; ?></title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/header.css">
</head>
<header>
    <h1>Naturaleza: Red Natura 2000</h1>
    <p><strong>Explorando el reino animal especie por especie</strong></p>
    <nav>
        <ul>
            <li><a href="/Eurofilm/public/movies/movies.php">Inicio</a></li>
            <li><a href="<?php echo RUTA_URL; ?>/AnimalController/list">Películas Populares</a></li>
            <?php if(isset($_SESSION['nombre'])) : ?>
                <li><a href="<?= RUTA_URL ?>/UsuarioController/detalle">Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/logout">Cerrar Sesión</a></li>
            <?php else : ?>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/login">Login</a></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/registrarse">Sign up</a></li>
            <?php endif; ?>
            <li><a href="<?php echo RUTA_URL; ?>/AnimalController/especies">Especies</a></li>
            <li><a href="<?php echo RUTA_URL; ?>/AnimalController/addAnimal">Agregar un animal</a></li>
        </ul>
    </nav>
</header>
<main class="contenedor">