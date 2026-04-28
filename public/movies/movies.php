<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eurofilm</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/global.css">
    <script defer src="../js/main.js" type="module"></script>
</head>

<body>
<?php include '../inc/header.php'; ?>
<input type="text" id="busqueda-titulo" placeholder="Busca tu película...">
<button id="btn-buscar">
    <i class="fas fa-search"></i>Buscar</button>
<select id="select-pais">
    <option value="">Cualquier país</option>
    <option value="ES">España</option>
    <option value="US">EE.UU.</option>
</select>
<select id="select-plataforma">
    <option value="">Todas las plataformas</option>
    <option value="8">Netflix</option>
    <option value="337">Disney+</option>
    <option value="350">Apple TV</option>
</select>
<div id="peliculas"></div>
</body>

</html>