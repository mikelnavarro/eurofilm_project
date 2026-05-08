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
        $sql = "INSERT INTO movies (tmdb_id, title, release_date, poster_path)
            VALUES (:tmdb_id, :title, :release_date, :poster)";

        $this->db->execute($sql, [
            'tmdb_id' => $tmdbId,
            'title' => $title,
            'release_date' => $releaseDate,
            'poster' => $poster,
        ]);

        return $this->db->lastInsertId();
    }
}
