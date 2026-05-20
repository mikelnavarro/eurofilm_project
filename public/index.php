<?php

// La sesión debe iniciarse antes de cargar las vistas
session_start();
// Cargar el autoloader
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();
// 3. Arrancar el Core
// Como usas namespaces, instancia el Core desde su ubicación
$app = new \Mikelnavarro\Eurofilm\core\Core();

