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
    public function saveReview($userId, $movieId, $rating, $titulo, $comment = null, $visibility = "Privada", $spoiler = 0)
    {
        $this->db->query("
        INSERT INTO reviews
        (user_id, movie_tmdb_id, rating, title, comment, visibility, spoiler)
        VALUES
        (:user_id, :tmdb_id, :rating, :titulo, :comment, :visibility, :spoiler)
    ");

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':tmdb_id', $movieId);
        $this->db->bind(':rating', $rating);
        $this->db->bind(':title', $titulo);
        $this->db->bind(':comment', $comment);
        $this->db->bind(':visibility', $visibility);
        $this->db->bind(':spoiler', $spoiler);


        return $this->db->execute();
    }
    // obtener reviews de una pelicula
    public function getByMovie($movieId)
    {
        $sql = "SELECT r.*, u.name
            FROM reviews r
            INNER JOIN users u ON u.id = r.user_id
            WHERE r.movie_id = :movie_id
            ORDER BY r.id DESC";

        $this->db->query($sql);
        $this->db->bind(':movie_id', $movieId);

        return $this->db->registros();
    }
}
