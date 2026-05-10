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
    // cambiar visibilidad de lista
    public function changeFavoritesVisibility()
    {
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse(['error' => 'No autenticado'], 401);
            return;
        }
        $userId = $_SESSION['usuario']['id'];
        $visibility = $_POST['visibility'];

        $listId = $this->ListModel->getOrCreateFavorites($userId);

        $ok = $this->ListModel->updateVisibility($listId, $visibility);

        $this->jsonResponse(['ok' => $ok]);
    }
    // borrar de Favoritos
    public function removeFavorite()
    {
        $userId = $_SESSION['usuario']['id'];
        $movieId = $_POST['movie_id'];

        $listId = $this->ListModel->getOrCreateFavorites($userId);

        $ok = $this->ListModel->removeMovie($listId, $movieId);

        $this->jsonResponse([
            'ok' => $ok
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


        $tmdbId = $_GET['id'];
        $userId = $_SESSION['usuario']['id'];
        $movieId = $_POST['movie_id'];
        $rating  = $_POST['rating'];
        $titulo = $_POST['review-title'] ?? '';
        $comment = $_POST['comment'] ?? '';
        $visibility = $_POST['visibility'] ?? "Privada";


        // spoiler
        $spoiler = $_POST["spoiler"] ?? '0';
        if (!$movieId || !$rating) {
            $this->jsonResponse(['error' => 'Datos incompletos'], 400);
            return;
        }

        // 1. obtener datos de la peli desde TMDB o frontend
        $movieData = $this->tmdb->getMovie($tmdbId);


        // 2. asegurar película en BD
        $movieId = $this->MovieModel->create(
            $tmdbId,
            $movieData['title'],
            $movieData['poster_path'],
            $movieData['release_date']
        );
        // insertamos en review

        // porque tenemos
        $this->reviewModel->saveReview(
            $userId,
            $movieId,
            $rating,
            $titulo,
            $comment,
            $visibility,
            $spoiler
        );

        $this->jsonResponse(['ok' => true]);
    }
}
