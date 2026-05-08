<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\Core\Controller;

class MovieController extends Controller
{
    // Atributos
    private $tmdb;
    private $modelo;

    // Constructor
    public function __construct()
    {
        $this->modelo = $this->modelo('Movie');
        $this->modelo = $this->modelo('List');
        $this->tmdb = $this->service('TmdbService');
    }
    public function addFavorite()
    {
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse(['error' => 'No autenticado'], 401);
            return;
        }

        $userId = $_SESSION['usuario']['id'];

        $tmdbId = $_POST['tmdb_id'] ?? null;
        $title = $_POST['title'] ?? null;
        $poster = $_POST['poster'] ?? null;
        $date = $_POST['release_date'] ?? null;

        if (!$tmdbId) {
            $this->jsonResponse(['error' => 'Falta tmdb_id'], 400);
            return;
        }

        $ok = $this->modelo->addToFavorites(
            $userId,
            $tmdbId
        );

        $this->jsonResponse([
            'ok' => true,
            'created' => $ok
        ]);
    }
}
