<?php

namespace Mikelnavarro\Eurofilm\models;

use Mikelnavarro\Eurofilm\core\Db;
use PDO;
use PDOException;

class Movie
{
    // Atributos
    // Constructor
    protected $db;
    public function __construct()
    {
        $this->db = new Db();
    }

    // buscar por tmdb id importante
    public function getByTmdbId($tmdbId)
    {
        $sql = "SELECT * FROM movies WHERE tmdb_id = :tmdb_id LIMIT 1";
        $result = $this->db->query($sql, ['tmdb_id' => $tmdbId]);

        return $result ? $result[0] : null;
    }
    // create
    public function create($tmdbId, $title, $poster, $releaseDate)
    {
        $sql = "INSERT INTO movies (tmdb_id, title, poster_path, release_date)
            VALUES (:tmdb_id, :title, :poster, :release_date)";

        $this->db->execute($sql, [
            'tmdb_id' => $tmdbId,
            'title' => $title,
            'poster' => $poster,
            'release_date' => $releaseDate
        ]);

        return $this->db->lastInsertId();
    }
    // añadir a favoritos
    public function addToFavorites($userId, $tmdbId, $title, $poster, $date)
    {
        // Evitar duplicado
        $check = "SELECT id FROM lists 
              WHERE user_id = :user_id 
              AND name = 'Favoritos'
              AND tmdb_id = :tmdb_id";

        $exists = $this->db->query($check, [
            'user_id' => $userId,
            'tmdb_id' => $tmdbId
        ]);

        if ($exists) {
            return false;
        }

        // Insertar
        $sql = "INSERT INTO lists 
            (user_id, name, tmdb_id, title, poster_path, release_date)
            VALUES (:user_id, 'Favoritos', :tmdb_id, :title, :poster, :date)";

        return $this->db->execute($sql, [
            'user_id' => $userId,
            'tmdb_id' => $tmdbId,
            'title' => $title,
            'poster' => $poster,
            'date' => $date
        ]);
    }
}
