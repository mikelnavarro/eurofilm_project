<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\Core\Controller;

class MovieController extends Controller
{
    // Atributos
    private $tmdb;
    private $ListModel;
    private $MovieModel;

    // Constructor
    public function __construct()
    {
        $this->MovieModel = $this->modelo('Movie');
        $this->ListModel = $this->modelo('List');
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
        $releaseDate = $_POST['release_date'] ?? null;


        if (!$tmdbId) {
            $this->jsonResponse(['error' => 'Falta tmdb_id'], 400);
            return;
        }

        // Comprobamos
        $movie = $this->MovieModel->getByTmdbId($tmdbId);


        if (!$movie) {
            $movieId = $this->MovieModel->create(
                $tmdbId,
                $title,
                $poster,
                $releaseDate
            );
        } else {
            $movieId = $movie->id;
        }


        // Obtener Lista favoritos
        $listId = $this->ListModel->getOrCreateFavorites($userId);
        if ($this->ListModel->exists($listId, $movieId)) {
            $this->jsonResponse(['ok' => true, 'message' => 'Ya estaba en favoritos']);
            return;
        }
        $this->ListModel->addMovie($listId, $movieId);

        // Ok
        $ok = $this->ListModel->addToFavorites(
            $userId,
            $tmdbId
        );

        $this->jsonResponse([
            'ok' => true,
            'created' => $ok
        ]);
    }
}
