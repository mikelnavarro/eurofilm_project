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
    <script defer src="..js/favorites.js" type="module"></script>
</head>

<body>

    <h1>Mis películas favoritas</h1>
    <div id="favorites-container">
        <?php if (empty($movies)) : ?>
            <p>No tienes películas en favoritos aún.</p>
        <?php else : ?>

            <?php foreach ($movies as $movie) : ?>
                <div class="movie-card" data-id="<?= $movie->id ?>">

                    <img
                        src="https://image.tmdb.org/t/p/w300<?= htmlspecialchars($movie->poster_path) ?>"
                        alt="<?= htmlspecialchars($movie->title) ?>">

                    <div class="movie-info">
                        <h3><?= htmlspecialchars($movie->title) ?></h3>
                        <p>
                            <?= htmlspecialchars($movie->release_date) ?>
                        </p>

                        <button class="btn-remove-fav" data-id="<?= $movie->id ?>">
                            Quitar de favoritos
                        </button>

                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <script src="/Eurofilm/public/js/favorites.js"></script>

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