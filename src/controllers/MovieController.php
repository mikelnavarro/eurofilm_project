<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\Core\Controller;

class MovieController extends Controller
{
    // Atributos
    private $tmdb;
    private $ListModel;
    private $MovieModel;
    private $reviewModel;

    // Constructor
    public function __construct()
    {
        $this->MovieModel = $this->modelo('Movie');
        $this->ListModel = $this->modelo('Lista');
        $this->reviewModel = $this->modelo('Review');
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


        if (!$tmdbId) {
            $this->jsonResponse(['error' => 'Falta tmdb_id'], 400);
            return;
        }

        // Comprobamos
        $movie = $this->MovieModel->getByTmdbId($tmdbId);


        if (!$movie) {
            $tmdbData = $this->tmdb->getMovie($tmdbId);

            $movieId = $this->MovieModel->create(
                $tmdbId,
                $tmdbData['title'],
                $tmdbData['poster_path'],
                $tmdbData['release_date'],
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
        $ok = $this->ListModel->addMovie(
            $userId,
            $movieId
        );

        $this->jsonResponse([
            'ok' => true,
            'created' => $ok
        ]);
    }

    // Favoritos
    public function getFavoritos()
    {
        $userId = $_SESSION['usuario']['id'];

        $listId = $this->ListModel->getOrCreateFavorites($userId);
        $movies = $this->ListModel->getMoviesByListId($listId);

        $this->jsonResponse([
            'ok' => true,
            'movies' => $movies
        ]);
    }
    // Rating - guardar votacion de pelicula
    public function addReview()
    {
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse(['error' => 'No autenticado'], 401);
            return;
        }

        $tmdbId = $_POST['tmdb_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? null;

        if (!$tmdbId || !$rating) {
            $this->jsonResponse(['error' => 'Datos incompletos'], 400);
            return;
        }

        $this->reviewModel->create(
            $_SESSION['usuario']['id'],
            $tmdbId,
            $rating,
            $comment
        );

        $this->jsonResponse(['ok' => true]);
    }
}
