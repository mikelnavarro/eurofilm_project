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
    <script defer src="../js/reviews.js" type="module"></script>
    <!-- Servicios Externalizados Chats -->
    <script defer src="../js/rate.js" type="module"></script>
</head>

<body>
    <?php include '../inc/header.php'; ?>
    <main>
        <span id="mensaje"></span>
        <div id="movie-details">

            <!-- IZQUIERDA: INFO -->
            <div class="left-panel">
                <div id="movie-info">
                    <p id="movie-genres"></p>
                    <p id="country-flag"></p>
                    <h1 id="movie-title"></h1>
                    <div id="director">
                        <p id="created-by"></p>
                        <img id="directing_path" src="" alt="Poster">
                    </div>
                    <p id="movie-release-date"></p>
                    <div id="write">
                        <img id="directing_path" src="" alt="Poster">
                        <p id="writers"></p>
                    </div>
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
                <button id="btn-favorito">Añadir a Favoritos</button>
                <p id="movie-language"></p>
                <p id="movie-status"></p>
                <div class="meta-block">
                    <span class="label">Paises: </span>
                    <p id="countries"></p>
                </div>
                <div class="provider-container">
                    <a href="#" id="homepage" class="streaming-link">
                        <span class="icon">▶</span> PROVIDER/S
                    </a>
                </div>
                <div id="provider-container"></div>
                <div class="meta-block">
                    <span class="label">Productoras: </span>
                    <p id="companies"></p>
                </div>
                <?php include_once "rating.php"; ?>
                <div class="movie-rating-tmdb"></div>
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
        <h3>Reseñas de los Usuarios</h3>
        <div id="reviews-container">
            <div data-resena="<?= $_SESSION['usuario']['id'] ?>" class="review-card ${review.spoiler == 1 ? 'review-spoiler' : ''}"></div>
        </div>
        <script>
            const TMDB_ID = <?= json_encode($_GET['id']) ?>;
            const CURRENT_USER_ID = <?= $_SESSION['usuario']['id'] ?? 'null' ?>;
        </script>
    </main>
</body>
</html>