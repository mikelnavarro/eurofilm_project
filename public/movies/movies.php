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
    <script defer src="../js/main.js" type="module"></script>
    <script defer src="../js/filtros.js" type="module"></script>

    <!-- Servicios Externalizados Chats -->
    <script type="text/javascript" src="https://popupsmart.com/freechat.js"></script>
</head>

<body>
    <?php include '../inc/header.php'; ?>
    <?php include '../busqueda.php'; ?>
    <?php include 'filtros.php'; ?>
    <main>
        <!-- Películas Populares -->
        <h3>Películas populares</h3>
        <div id="peliculas"></div>
    </main>
    <div class="pagination-controls">
        <button id="prev-page">Anterior</button>
        <span id="current-page-display">Cargar Mas</span>
        <button id="next-page">Siguiente</button>
    </div>
    <?php include '../inc/footer.php'; ?>
</body>
    <script>
        window.start.init({
            title: "Hi there ✌️",
            message: "How may we help you? Just send us a message now to get assistance.",
            color: "#FA764F",
            position: "right",
            placeholder: "Enter your message",
            withText: "Write with",
            viaWhatsapp: "Or write us directly via Whatsapp",
            gty: "Go to your",
            awu: "and write us",
            connect: "Connect now",
            button: "Write us",
            device: "everywhere",
            logo: "https://d2r80wdbkwti6l.cloudfront.net/1FJbm6ECI9NXCXX1WPTZnyedW51585zz.jpg",
            services: [{
                "name": "mail",
                "content": "mikelnaval06@gmail.com"
            }, {
                "name": "whatsapp",
                "content": "669092418"
            }, {
                "name": "telegram",
                "content": "mikelnaval06"
            }, {
                "name": "phone",
                "content": "669092418"
            }]
        })
    </script>
</html>