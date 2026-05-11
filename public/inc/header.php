<?php session_start(); 
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
    <a href="<?php echo RUTA_URL; ?>/public/movies/movies.php"><img src="../image/favicon.ico" class="logo" alt="logo"></a>
    <h1>eurofilm</h1>
    <p><strong>Explorando el reino cinematográfico de TMDB</strong></p>
    <nav>
        <ul class="menu">
            <li><a href="/Eurofilm/public/movies/movies.php">Inicio</a></li>
            <li><a href="#">Películas</a>
                <ul class="submenu">
                    <li><a href="/Eurofilm/public/movies/movies.php">Pelis de Acción</a></li>
                    <li><a href="/Eurofilm/public/movies/movies.php">Populares</a></li>
                </ul>
            </li>
            <li><a href="/Eurofilm/public/movies/spanish.php">Spanish Movies</a></li>
            <li><a href="/Eurofilm/public/movies/series.php">Series en España</a></li>

            <?php if (isset($_SESSION['usuario'])) : ?>
                <li>
                    <a href="/Eurofilm/public/pantalla/perfil.php">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']['username']) ?? 'Usuario'; ?>
                        </strong>
                    </a>
                </li>
                <li><a href="/Eurofilm/public/pantalla/perfil.php">Ver Perfil</a></li>
                <li>
                    <a href="/Eurofilm/auth/logout">Cerrar Sesión</a>
                </li> <?php else : ?>
                <li><a href="/Eurofilm/public/pantalla/login.php">Login</a></li>
                <li><a href="/Eurofilm/public/pantalla/register.php">Sign up</a></li>
            <?php endif; ?>
            <li><a href="#">Contacto</a>
                <ul class="submenu">
                    <li><a href="/Eurofilm/public/referencia/quienes-somos">Quiénes somos</a></li>
                    <li><a href="#">Email</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<main class="contenedor">