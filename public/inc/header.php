<?php

require_once '../variables_config.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOMBRESITIO; ?></title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>public/css/header.css">
</head>
<header>
    <h1>eurofilm</h1>
    <p><strong>Explorando el reino cinematográfico de TMDB</strong></p>
    <nav>
        <ul class="menu">
            <li><a href="/Eurofilm/public/movies/movies.php">Inicio</a></li>
            <li><a href="#">Películas</a>
                <ul class="submenu">
                    <li><a href="#">Populares</a></li>
                    <li><a href="#">Top Rated</a></li>
                </ul>
            </li> <?php if (isset($_SESSION['nombre'])) : ?>
                <li><a href="<?= RUTA_URL ?>/UsuarioController/detalle">Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/logout">Cerrar Sesión</a></li>
            <?php else : ?>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/login">Login</a></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/registrarse">Sign up</a></li>
            <?php endif; ?>
            <li><a href="<?php echo RUTA_URL; ?>/AnimalController/ejemplo2">ejemplo 2</a></li>
            <li><a href="#">Categorías</a>
                <ul class="submenu">
                    <li><a href="#tecnologia">Tecnología</a></li>
                    <li><a href="#moda">Moda</a></li>
                    <li><a href="#hogar">Hogar</a></li>
                </ul>
            </li>
            <li><a href="#ofertas">Ofertas</a>
                <ul class="submenu">
                    <li><a>Ropa</a></li>
                    <li><a>Maquillaje</a></li>
                </ul>
            </li>
            <li><a href="#">Contacto</a>
                <ul class="submenu">
                    <li><a href="#">Email</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<main class="contenedor">