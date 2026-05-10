<?php
// suponemos sesión activa
$userId = $_SESSION['usuario']['id'];

if (!$userId) {
    die("No autorizado");
}

// controlador ya te pasa esto idealmente
// $movies = [...];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis favoritos</title>
    <link rel="stylesheet" href="/Eurofilm/public/css/style.css">
    <script defer src="../js/visibility.js" type="module"></script>
    <script defer src="../js/favorites.js" type="module"></script>
</head>

<body>

    <h1>Mis películas favoritas</h1>
    <form id="formulario-visibility" method="POST">
    <select id="visibility-fav">
        <option value="Privada">Privada</option>
        <option value="Publica">Publica</option>
    </select>
    <input type="submit" value="Cambiar Visibilidad de la Lista">
    </form>
    <div id="favorites-container" data-user-id="<?= $userId ?>"></div>
</body>

</html>
<style>
    .movie-card {
        display: flex;
        gap: 15px;
        margin: 15px 0;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .movie-card img {
        width: 120px;
        border-radius: 8px;
    }

    .movie-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    button {
        margin-top: 10px;
        padding: 6px 10px;
        cursor: pointer;
    }
</style>