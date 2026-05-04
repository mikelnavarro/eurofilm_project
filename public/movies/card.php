<?php

require_once '../variables_config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/tarjeta-peli.css">
    <script defer src="../js/detalles.js" type="module"></script>

</head>

<body>
    <?php include '../inc/header.php'; ?>
    <main>
        <div id="movie-details">

            <!-- IZQUIERDA: INFO -->
            <div id="left-panel">
                <!-- ACTRICES -->
                <div id="movie-cast">
                    <h3>Elenco</h3>
                    <ul id="cast"></ul>
                </div>
                <div id="movie-info">
                    <h1 id="movie-title"></h1>
                    <p id="movie-release-date"></p>
                    <p id="movie-overview"></p>
                    <p id="movie-genres"></p>
                </div>
            </div>
                <!-- DERECHA: POSTER -->
                <div id="right-panel">
                    <img id="movie-poster" src="" alt="Poster">
                    <p id="movie-genres"></p>
                    <p id="movie-language"></p>
                    <p id="movie-status"></p>
                    <p id="countries"></p>
                    <p id="companies"></p>
                    <div id="trailer-container">
                        <iframe id="movie-trailer" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <button id="btn-favorito">Añadir a Favoritos</button>
                </div>
            </div>
        </div>

    </main>
</body>

</html>