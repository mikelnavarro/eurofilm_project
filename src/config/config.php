<?php
return [
    // Configuración de la Base de Datos
    'db' => [
        'host'     => 'localhost',
        'dbname'   => 'eurofilm_db',
        'user'     => 'dweb',
        'pass'     => '12345',
        'charset'  => 'utf8mb4',
    ],
    // Configuración de TMDB
    'tmdb' => [
        'api_key' => 'af29f8a0d4727927172c7825408f2cda',
        'token' => 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJhZjI5ZjhhMGQ0NzI3OTI3MTcyYzc4MjU0MDhmMmNkYSIsIm5iZiI6MTc3NjI4MDQyMC43NTIsInN1YiI6IjY5ZGZlMzY0MTY4N2RiZGU1NmY5NDA5NyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.KMJVXb5n0eWifW6HiJn67amyw2H9xN15dcv6g3LIxus',
        'base_url' => 'https://api.themoviedb.org/3',
        'img_url'  => 'https://image.tmdb.org/t/p/w500'
    ],
    'rutas' =>  [],
    'site' => [
        'name' => 'Eurofilm',
        'url'  => 'http://localhost/Eurofilm/public'
    ]
];
