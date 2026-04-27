<?php
return [
    // Configuración de la Base de Datos
    'db' => [
        'host'     => 'localhost',
        'dbname'   => 'eurofilm_db',
        'user'     => 'root',
        'pass'     => '',
        'charset'  => 'utf8mb4',
    ],
    // Configuración de TMDB
    'tmdb' => [
        'api_key' => '4d3faecd2785dc35f1635f8d03d69e8e',
        'token' => 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZDNmYWVjZDI3ODVkYzM1ZjE2MzVmOGQwM2Q2OWU4ZSIsIm5iZiI6MTc3NjI4MDQyMC43NTIsInN1YiI6IjY5ZGZlMzY0MTY4N2RiZGU1NmY5NDA5NyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8V7izLrCtRLetzuCAPWnWLW3aUEuvl-LSkdg8KHqhIE',
        'base_url' => 'https://api.themoviedb.org/3',
        'img_url'  => 'https://image.tmdb.org/t/p/w500'
    ],
    'rutas' =>  [],
    'site' => [
        'name' => 'Eurofilm',
        'url'  => 'http://localhost/pelis/public'
    ]
];
