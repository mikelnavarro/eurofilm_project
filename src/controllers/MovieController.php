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
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'No autenticado'
            ], 401);
            return;
        }

        $userId = $_SESSION['usuario']['id'];
        $movieId = $_POST['movie_id'];
        if (!$movieId) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'Movie ID requerido'
            ], 400);
            return;
        }
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
        var_dump($_POST);
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse(['error' => 'No autenticado'], 401);
            return;
        }
        $tmdbId = $_POST['tmdbId'] ?? null;
        $userId = $_SESSION['usuario']['id'];
        $rating = $_POST['rating'] ?? null;
        $titulo = $_POST['review-title'] ?? '';
        $comment = $_POST['comment'] ?? '';
        $visibility = $_POST['visibility'] ?? 'Privada';
        $spoiler = $_POST['spoiler'] ?? '0';


        if (!$tmdbId || !$rating) {
            $this->jsonResponse(['error' => 'Datos incompletos'], 400);
            return;
        }
        // Buscar película en BD
        $movie = $this->MovieModel->getByTmdbId($tmdbId);


        // Si NO existe → crear
        if (!$movie) {
            $movieData = $this->tmdb->getMovie($tmdbId);

            // 2. asegurar película en BD
            $movieId = $this->MovieModel->create(
                $tmdbId,
                $movieData['title'],
                $movieData['poster_path'],
                $movieData['release_date']
            );
        } else {
            // ya existe
            $movieId = $movie->id;
        }

        // insertamos en review

            // 2. comprobar si existe review
    $existing = $this->reviewModel->userHasReviewed($userId, $movieId);

    if ($existing) {

        $this->reviewModel->updateReview(
            $userId,
            $movieId,
            $rating,
            $titulo,
            $comment,
            $visibility,
            $spoiler
        );

        $this->jsonResponse([
            'ok' => true,
            'updated' => true
        ]);
        return;
    }
        // guardar review
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


    // obtener todas
    public function getReviews()
    {
        $tmdbId = $_GET['tmdb_id'] ?? null;

        if (!$tmdbId) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'TMDB ID requerido'
            ]);
            return;
        }

        // película interna
        $movie = $this->MovieModel->getByTmdbId($tmdbId);

        if (!$movie) {
            $this->jsonResponse([
                'ok' => true,
                'reviews' => []
            ]);
            return;
        }

        $reviews = $this->reviewModel->getByMovieId($movie->id);
        $this->jsonResponse([
            'ok' => true,
            'reviews' => $reviews
        ]);
    }


    // obtener media de reseña
    public function getMediaByMovie()
    {


        // comprobamos
        if (!isset($_GET['tmdb_id'])) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'TMDB ID requerido'
            ], 400);
            return;
        }


        // obtenemos la peli
        $tmdbId = $_GET['tmdb_id'];

        // obtener película interna
        $movie = $this->MovieModel->getByTmdbId($tmdbId);
        if (!$movie) {
            $this->jsonResponse([
                'ok' => true,
                'average' => 0,
                'total' => 0
            ]);
            return;
        }
        // Pedir estos datos al modelo
        $result = $this->reviewModel->getAverageRating($movie->id);

        // normalizar json salida
        $this->jsonResponse([
            'ok' => true,
            'average' => round((float)($result->average_rating ?? 0), 1),
            'total' => (int)($result->total_reviews ?? 0)
        ]);
    }
    // obtener reviews de un usuario concretamente
    public function getUserReviews()
    {
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'No autenticado'
            ], 401);
            return;
        }

        $userId = $_SESSION['usuario']['id'];
        $reviews = $this->reviewModel->getByUserId($userId);

        $this->jsonResponse([
            'ok' => true,
            'reviews' => $reviews
        ]);
    }

    // remove
    public function deleteReview()
    {
        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'No autenticado'
            ], 401);
            return;
        }

        $reviewId = $_POST['review_id'] ?? null;
        $userId = $_SESSION['usuario']['id'];

        if (!$reviewId) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'ID requerido'
            ], 400);
            return;
        }

        $deleted = $this->reviewModel->deleteReview($reviewId, $userId);

        $this->jsonResponse([
            'ok' => $deleted
        ]);
    }
    public function hasReviewed()
    {
        $userId = $_SESSION['usuario']['id'];
        $movieId = $_GET['movie_id'];

        $result = $this->reviewModel->userHasReviewed($userId, $movieId);

        $this->jsonResponse([
            'ok' => true,
            'hasReviewed' => $result ? true : false
        ]);
    }
}
