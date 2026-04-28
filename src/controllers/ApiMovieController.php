<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\Core\Controller;

class ApiMovieController extends Controller
{
    // Atributos
    private $tmdb;
    private $modelo;

    // Constructor
    public function __construct()
    {
        $this->modelo = $this->modelo('Movie');
        $this->tmdb = $this->service('TmdbService');
    }


    public function index()
    {
        header('Content-Type: text/html; charset=utf-8');
        echo "<h1>Bienvenido a Eurofilm</h1>";
        echo "El sistema de rutas está funcionando correctamente.";
        exit();
    }


    // GET /api/movies
    public function movies(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->jsonResponse(["error" => "Method Not Allowed"], 405);
        }

        $pagina = $_GET['page'] ?? 1;

        $data = $this->tmdb->consultar('/movie/popular', [
            'page' => $pagina
        ]);

        $this->jsonResponse($data, 200);
    }


    public function addFavorito(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method Not Allowed'], 405);
        }

        $data = $this->getJSON();

        $movieId = $data['movie_id'] ?? null;

        if (!$movieId) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        $this->modelo = $this->modelo('List');
        $resultado = $this->modelo->addFavorito($movieId);
        $this->jsonResponse(['success' => $resultado]);
    }
}
