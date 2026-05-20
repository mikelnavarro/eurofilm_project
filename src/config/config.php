<?php
return [
    // Configuración de la Base de Datos
    'db' => [
        'host'     => $_ENV["DB_HOST"],
        'dbname'   => $_ENV["DB_NAME"],
        'user'     => $_ENV["DB_USER"] ?? 'root',
        'pass'     => $_ENV["DB_PASS"] ?? '',
        'charset'  => $_ENV["DB_CHARSET"] ?? 'utf8mb4'
    ],
    // Configuración de TMDB
    'tmdb' => [
        'api_key' => $_ENV["API_KEY"],
        'token' => $_ENV["TOKEN_TMDB"],
        'base_url' => $_ENV["BASE_URL"],
        'img_url'  => $_ENV["IMG_URL"]
    ],
    'rutas' =>  [],
    // Sitio web
    'site' => [
        'name' => $_ENV["SITE"],
        'url'  => $_ENV["URL"] ?? 'http://localhost/Eurofilm/'
    ]
];
