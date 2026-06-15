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
        $data = "Bienvenido a Eurofilm";
        $data = "El sistema de rutas está funcionando correctamente.";
        $this->jsonResponse($data, 201);
        exit();
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
        exit();
    }
    // GET /api/movie
    public function movie($param = null): void
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

        // Ruta completa append
        $rutaCompleta = "/movie/$id?append_to_response=credits,videos,watch/providers";
        $data = $this->tmdb->consultar($rutaCompleta);

        // procesar los créditos si existen en la respuesta unificada
        if (!empty($data['credits'])) {
            $data['crew'] = $data['credits']['crew'] ?? [];
            $data['cast'] = array_slice($data['credits']['cast'] ?? [], 0, 5);
            unset($data['credits']);
        } else {
            $data['crew'] = [];
            $data['cast'] = [];
        }
        $trailer = null;
        if (!empty($data['videos']['results'])) {
            foreach ($data['videos']['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer = $video['key'];
                    break;
                }
            }
            unset($data['videos']); // Limpieza opcional
        }
        $data["trailer"] = $trailer;

        // procesar los proveedores desde el bloque
        if (!empty($data['watch/providers'])) {
            $data['watch_providers'] = $data['watch/providers'];
            unset($data['watch/providers']); // Limpieza opcional
        } else {
            $data['watch_providers'] = null;
        }

        $this->jsonResponse($data, 200);
        exit;
    }


    // Buscar películas
    public function search(): void
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
    public function spanishMovies(): void
    {
        $page = $_GET['page'] ?? 1;

        $data = $this->tmdb->consultar('/discover/movie', [
            'with_origin_country' => 'ES',
            'with_original_language' => 'es',
            "sort_by" => "popularity.desc",
            'page' => $page
        ]);



        $this->jsonResponse($data, 200);
        exit;
    }
    public function searchSeries(): void
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
        // Una unica peticion
        $rutaCompleta = "/tv/$id?append_to_response=credits,videos,watch/providers";
        $data = $this->tmdb->consultar($rutaCompleta);

        
        // procesar los creditos
        if (!empty($data['credits'])) {
            $data['cast'] = array_slice($data['credits']['cast'] ?? [], 0, 5);
            unset($data['credits']);
        } else {
            $data['cast'] = [];
        }

        // procesamos el trailer
        $trailer = null;
        if (!empty($data['videos']['results'])) {
            foreach ($data['videos']['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer = $video['key'];
                    break;
                }
            }
            unset($data['videos']); // Limpieza
        }
        $data["trailer"] = $trailer;
        // los proveedores de la tele
        if (!empty($data['watch/providers'])) {
            $data['watch_providers'] = $data['watch/providers'];
            unset($data['watch/providers']);
        } else {
            $data['watch_providers'] = null;
        }



        header('Content-Type: application/json');
        $this->jsonResponse($data, 200);
        exit;
    }
}
