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
        <input type="submit" class="visibility" id="visibility" value="Cambiar Visibilidad de la Lista">
    </form>
    <div id="favorites-container" data-user-id="<?= $userId ?>"></div>
</body>

</html>
<style>
    h1 {
        font-size: 2.2rem;
        color: #1d3557;
        /* Navy */
        margin-bottom: 20px;
        position: relative;
    }

    h1::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #e63946, #800000);
        margin-top: 10px;
    }

    /* CONTENEDOR GRID */
    #favorites-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, 170px);
        gap: 25px;
        padding: 20px 0;
        margin: 0;
        justify-items: start; 
        justify-content: start; 
        
    }

    .movie-card {
        display: flex;
        gap: 15px;
        margin: 15px 0;
        flex-direction: column;
        padding: 10px;
        border: 1px solid #ddd;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .movie-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(29, 53, 87, 0.2);
        border-color: #457b9d;
    }

    .movie-card img {
        width: 120px;
        border-radius: 8px;
        border-radius: 0;

    }

    .movie-info {
        display: flex;
        flex-direction: column;
        text-align: center;
        justify-content: center;
    }

    .movie-info h3 {
        /* Suponiendo que el JS inyecta un h3 */
        font-size: 1rem;
        color: #1d3557;
        margin: 0 0 10px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .remove-fav, .visibility {
        margin-top: 10px;
        background: #f1faee;
        color: #e63946;
        border: 1px solid #e63946;
        border-radius: 6px;
        padding: 8px 10px;
        font-weight: bold;
        width: 100%;
        transition: 0.3s;
    }

    .visibility {
        margin-top: 10px;
        padding: 6px 10px;
        cursor: pointer;
    }

    .visibility:hover {
        color: white;
        background: #e63946;
    }
</style>