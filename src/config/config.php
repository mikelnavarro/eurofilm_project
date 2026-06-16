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
    // Mailer
        'mail' => [
        'host'      => $_ENV['MAIL_HOST'] ?? '',
        'port'      => $_ENV['MAIL_PORT'] ?? 587,
        'username'  => $_ENV['MAIL_USERNAME'] ?? '',
        'password'  => $_ENV['MAIL_PASSWORD'] ?? '',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'Sistema',
        'charset'   => $_ENV['MAIL_CHARSET'] ?? 'UTF-8',
    ],
    'rutas' =>  [],
    // Sitio web
    'site' => [
        'token_expiry'     => $_ENV['TOKEN_RESET_EXPIRY'] ?? 3600,
        'name' => $_ENV["SITE"],
        'url'  => $_ENV["URL"] ?? 'http://localhost/Eurofilm/'
    ]
];
