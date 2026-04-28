<?php

namespace Mikelnavarro\Eurofilm\controllers;
use Mikelnavarro\Eurofilm\core\Controller;
use Mikelnavarro\Eurofilm\services\TmdbService;


class MovieController extends Controller
{
    private $tmdb;

    public function __construct() {
        $this->tmdb = new TmdbService();
    }
    public function index()
    {
        header('Content-Type: text/html; charset=utf-8');
        echo "<h1>Bienvenido a Eurofilm</h1>";
        echo "El sistema de rutas está funcionando correctamente.";
        exit();
    }


    public function getAll()
    {
        $pagina = $_GET['page'] ?? 1;

        $datos = $this->tmdb->consultar('/movie/popular');
        header('Content-Type: application/json');
        echo $this->json($datos);
        exit;
    }
}