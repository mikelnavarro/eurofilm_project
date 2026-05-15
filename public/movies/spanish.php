<?php
require_once '../variables_config.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eurofilm</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/global.css">
    <script defer src="../js/busqueda.js" type="module"></script>
    <script defer src="../js/spanishMovies.js" type="module"></script>
</head>

<body>

    <?php include '../inc/header.php'; ?>
    <?php include '../busqueda.php'; ?>
    <?php include 'filtros.php'; ?>
    <main>

        <h2>Películas Españolas</h2>
        <div id="peliculas"></div>
    </main>

    <div class="pagination-controls">
        <button id="prev-page">Anterior</button>
        <span id="current-page-display">Página 1</span>
        <button id="next-page">Siguiente</button>
    </div>
    <?php include '../inc/footer.php'; ?>

</body>

</html>