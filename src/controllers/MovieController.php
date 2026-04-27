<?php

namespace Mikelnavarro\Eurofilm\Controllers;
use Mikelnavarro\Eurofilm\Services\TmdbService;
class MovieController
{
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TmdbService();
    }
    public function index()
    {
        echo "<h1>Bienvenido a Eurofilm</h1>";
        echo "El sistema de rutas está funcionando correctamente.";

        $pagina = $_GET['page'] ?? 1;

        $datos = $this->tmdb->consultar('/movie/popular', [
            'page' => $pagina
        ]);
        header('Content-Type: application/json');
        echo $datos;
        exit;
    }
}