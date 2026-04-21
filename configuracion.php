<?php
return [
    'app' => [
        'nombre' => 'FilmCatálogo',
        'url'    => 'http://localhost/file/',
        'version' => '1.0.0'
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'tfg',
        'user' => 'dweb',
        'pass' => '',
        'charset' => 'utf8mb4'
    ],
    'paths' => [
        'root' => dirname(__FILE__),
        'controllers' => dirname(__FILE__) . '/src/Controller/',
        'models' => dirname(__FILE__) . '/src/Model/'
    ]
];