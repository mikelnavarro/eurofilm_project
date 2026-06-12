<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\Core\Controller;

class ApiController extends Controller
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
        //header('Content-Type: text/json; charset=utf-8');
        echo "<h1>Bienvenido a Eurofilm</h1>";
        echo "El sistema de rutas está funcionando correctamente.";
        //exit();
    }


    // GET /api/movies
    public function movies(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->jsonResponse(["error" => "Method Not Allowed"], 405);
        }

        $page = $_GET['page'] ?? 1;


        $data = $this->tmdb->consultar('/movie/popular', [
            "sort_by" => "popularity.desc",
            'page' => $page
        ]);

        $this->jsonResponse($data, 200);
    }
    // GET /api/movie
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
        $data['crew'] = $credits['crew'];
        $data['cast'] = array_slice($credits['cast'], 0, 5);
        // TRAILER
        $videos = $this->tmdb->consultar("/movie/$id/videos");

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
        // PROVEEDORES
        $watch_providers = $this->tmdb->consultar("/movie/$id/watch/providers");
        $data['watch_providers'] = $watch_providers;

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
        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }
    // Películas Españolas
    public function spanishMovies()
    {
        $page = $_GET['page'] ?? 1;

        $data = $this->tmdb->consultar('/discover/movie', [
            'with_origin_country' => 'ES',
            'with_original_language' => 'es',
            "sort_by" => "popularity.desc",
            'page' => $page
        ]);



        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }
    public function searchSeries()
    {
        $country = $_GET['with_origin_country'] ?? null; // Recibimos el país si existe
        $query = $_GET['query'] ?? null;
        $page = $_GET['page'] ?? 1;

        if (!$query) {
            $this->jsonResponse(['error' => 'Query requerida'], 400);
        }

        $data = $this->tmdb->consultar('/search/tv', [
            'query' => $query,
            'page' => $page
        ]);
        if ($country) {
            $data['results'] = array_values(array_filter($data['results'], function ($movie) use ($country) {
                return isset($movie['origin_country']) && in_array($country, $movie['origin_country']);
            }));
        }
        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }
    public function series()
    {
        $page = $_GET['page'] ?? 1;


        $data = $this->tmdb->consultar('/tv/on_the_air', [
            'with_original_language' => 'es-ES',
            'page' => $page,
            "sort_by" => "popularity.desc",
        ]);
        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }

    // serie
    public function serie($param = null)
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
        $data = $this->tmdb->consultar("/tv/$id");
        // CRÉDITOS
        $credits = $this->tmdb->consultar("/tv/$id/credits");
        $data['cast'] = array_slice($credits['cast'], 0, 5);



        // TRAILER
        $videos = $this->tmdb->consultar("/tv/$id/videos");
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
        // PROVEEDORES
        $watch_providers = $this->tmdb->consultar("/tv/$id/watch/providers");
        $data['watch_providers'] = $watch_providers;



        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }
}
