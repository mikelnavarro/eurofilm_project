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
    <link rel="stylesheet" href="../css/rating.css">
    <script defer src="../js/detalles.js" type="module"></script>
    <script defer src="../js/rate.js" type="module"></script>
</head>

<body>
    <?php include '../inc/header.php'; ?>
    <main>
        <div id="movie-details">

            <!-- IZQUIERDA: INFO -->
            <div class="left-panel">
                <div id="movie-info">
                    <p id="movie-genres"></p>
                    <h1 id="movie-title"></h1>
                    <p id="movie-release-date"></p>
                    <p id="movie-overview"></p>
                </div>
                <!-- ACTRICES -->
                <div id="movie-cast">
                    <h3>Elenco</h3>
                    <ul id="cast"></ul>
                </div>
            </div>
            <!-- DERECHA: POSTER -->
            <div class="right-panel">
                <img id="movie-poster" src="" alt="Poster">
                <p id="country-flag"></p>
                <button id="btn-favorito">Añadir a Favoritos</button>
                <p id="movie-language"></p>
                <p id="movie-status"></p>
                <div class="meta-block">
                    <span class="label">Paises: </span>
                    <p id="countries"></p>
                </div>
                <div class="provider-container">
                    <a href="#" id="link-provider" class="streaming-link">
                        <span class="icon">▶</span> PROVIDER/S
                    </a>
                </div>
                <div class="meta-block">
                    <span class="label">Productoras: </span>
                    <p id="companies"></p>
                </div>
                <?php include_once "rating.php"; ?>
                <div class="movie-rating"></div>
            </div>
        </div>
        </div>
        <?php
        $tmdb_id = $_GET['id'] ?? null;
        if (!$tmdb_id) {
            die("ID de película no válido");
        }
        ?>
        <div id="movie-data" data-tmdb="<?= htmlspecialchars($tmdb_id) ?>"></div>

    </main>
</body>

</html>