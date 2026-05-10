<?php

namespace Mikelnavarro\Eurofilm\models;

use Mikelnavarro\Eurofilm\core\Db;
use PDO;
use PDOException;

class Lista
{

    // atributos
    private $db;


    public function __construct()
    {
        $this->db = new Db();
    }

    // 1. obtener o crear lista Favoritos
    public function getOrCreateFavorites($userId)
    {
        $sql = "SELECT id FROM lists 
                WHERE user_id = :user_id 
                AND title = 'Favoritos'
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        $list = $this->db->registro();


        if ($list) {
            return $list->id;
        }

        $insert = "INSERT INTO lists (user_id, title)
                   VALUES (:user_id, 'Favoritos')";
        $this->db->query($insert);
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
        return $this->db->lastInsertId();
    }
    // 2. añadir película a lista
    public function addMovie($listId, $movieId)
    {
        $sql = "INSERT INTO list_movies (list_id, movie_id)
            VALUES (:list_id, :movie_id)";

        $this->db->query($sql);
        $this->db->bind(':list_id', $listId);
        $this->db->bind(':movie_id', $movieId);
        return $this->db->execute();
    }



    // si existe
    public function exists($listId, $movieId)
    {
        $sql = "SELECT 1 FROM list_movies 
            WHERE list_id = :list_id AND movie_id = :movie_id
            LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':list_id', $listId);
        $this->db->bind(':movie_id', $movieId);
        return $this->db->registro() !== null;
    }
    // favoritos - borrar
    public function removeMovie($listId, $movieId)
    {
        $sql = "DELETE FROM list_movies 
            WHERE list_id = :list_id 
            AND movie_id = :movie_id
            LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':list_id', $listId);
        $this->db->bind(':movie_id', $movieId);

        return $this->db->execute();
    }
    // favoritos - comprobar
    public function getMoviesByListId($listId)
    {
        $sql = "SELECT m.*
            FROM movies m
            INNER JOIN list_movies lm ON lm.movie_id = m.id
            WHERE lm.list_id = :list_id";

        $this->db->query($sql);
        $this->db->bind(':list_id', $listId);

        return $this->db->registros();
    }
}
