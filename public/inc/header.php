<?php
require_once '../variables_config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../image/favicon.ico" type="image/x-icon">
    <title><?= NOMBRESITIO ?? 'Eurofilm' ?></title>
    <link rel="stylesheet" href="../css/header.css">
</head>
<header>
    <a href="<?php RUTA_URL; ?>"><img src="../image/favicon.ico" class="logo" alt="logo"></a>
    <h1>eurofilm</h1>
    <p><strong>Explorando el reino cinematográfico de TMDB</strong></p>
    <nav>
        <ul class="menu">
            <li><a href="/Eurofilm/public/movies/movies.php">Inicio</a></li>
            <li><a href="#">Películas</a>
                <ul class="submenu">
                    <li><a href="/Eurofilm/public/movies/movies.php">Populares</a></li>
                </ul>
            </li> <?php if (isset($_SESSION['nombre'])) : ?>
                <li><a href="<?= RUTA_URL ?>/UsuarioController/detalle">Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/logout">Cerrar Sesión</a></li>
            <?php else : ?>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/login">Login</a></li>
                <li><a href="<?php echo RUTA_URL; ?>/UsuarioController/registrarse">Sign up</a></li>
            <?php endif; ?>
            <li><a href="#">Contacto</a>
                <ul class="submenu">
                    <li><a href="#">Quiénes somos</a></li>
                    <li><a href="#">Email</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<main class="contenedor">