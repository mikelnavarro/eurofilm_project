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


    public function movie($param = null)
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
        }


        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Content-Type: application/json');
            $this->jsonResponse(['error' => 'ID requerido'], 400);
            exit;
        }

        // DETALLES PRINCIPALES
        $data = $this->tmdb->consultar("/movie/$id");
        // CRÉDITOS
        $credits = $this->tmdb->consultar("/movie/$id/credits");
        $data['cast'] = array_slice($credits['cast'], 0, 5);

        // TRAILER
        $videos =
            $this->tmdb->consultar("/movie/$id/videos");

        $trailer = null;
        if (!empty($videos['results'])) {
            foreach ($videos['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer = $video['key'];
                    break;
                }
            }
        }

        $data["trailer"] = $trailer;



        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }


    // Buscar películas
    public function search()
    {
        $query = $_GET['query'] ?? null;
        $page = $_GET['page'] ?? 1;

        if (!$query) {
            $this->jsonResponse(['error' => 'Query requerida'], 400);
        }

        $data = $this->tmdb->consultar('/search/movie', [
            'query' => $query,
            'page' => $page
        ]);

        $this->jsonResponse($data, 200);
        exit;
    }
    // Películas Españolas
    public function spanishMovies()
    {
        $page = $_GET['page'] ?? 1;

        $data = $this->tmdb->consultar('/discover/movie', [
            'with_original_language' => 'es',
            'page' => $page
        ]);

        $this->jsonResponse($data, 200);
        exit;
    }

    // Para añadir a favoritos (listas)
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
