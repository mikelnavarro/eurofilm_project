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
        $this->db->query($sql);
        $this->db->bind(':tmdb_id', $tmdbId);

        return $this->db->registro();
    }
    public function getMoviesByListId($id){
        $sql = "SELECT * FROM movies WHERE id = :id LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }
    // create
    public function create($tmdbId, $title, $poster, $releaseDate)
    {
        $sql = "INSERT INTO movies (tmdb_id, title, release_date, poster_path)
            VALUES (:tmdb_id, :title, :release_date, :poster)";

        $this->db->query($sql);
        $this->db->bind(':tmdb_id', $tmdbId);
        $this->db->bind(':title', $title);
        $this->db->bind(':release_date', $releaseDate);
        $this->db->bind(':poster', $poster);

        $this->db->execute();


        return $this->db->lastInsertId();
    }
}
