<?php

namespace Mikelnavarro\Eurofilm\models;

use Mikelnavarro\Eurofilm\core\Db;
use PDO;
use PDOException;

class Review
{



    // atributo
    protected $db;
    // Constructor
    public function __construct()
    {
        $this->db = new Db();
    }
    // métodos
    public function create($userId, $tmdbId, $rating, $comment = null)
    {
        $this->db->query("
        INSERT INTO reviews
        (user_id, movie_tmdb_id, rating, comment)
        VALUES
        (:user_id, :tmdb_id, :rating, :comment)
    ");

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':tmdb_id', $tmdbId);
        $this->db->bind(':rating', $rating);
        $this->db->bind(':comment', $comment);

        return $this->db->execute();
    }
}
