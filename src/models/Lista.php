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
    public function getOrCreateFavoritesList($userId)
    {
        $sql = "SELECT id FROM lists 
                WHERE user_id = :user_id 
                AND name = 'Favoritos'
                LIMIT 1";

        $list = $this->db->query($sql, [
            'user_id' => $userId
        ]);

        if ($list) {
            return $list[0]->id;
        }

        $insert = "INSERT INTO lists (user_id, name)
                   VALUES (:user_id, 'Favoritos')";

        $this->db->execute($insert, [
            'user_id' => $userId
        ]);

        return $this->db->lastInsertId();
    }
    // 2. añadir película a lista
    public function addMovie($listId, $movieId)
    {
        $sql = "INSERT INTO list_movies (list_id, movie_id)
            VALUES (:list_id, :movie_id)";

        return $this->db->execute($sql, [
            'list_id' => $listId,
            'movie_id' => $movieId
        ]);
    }



    // si existe
    public function exists($listId, $movieId)
    {
        $sql = "SELECT id FROM list_movies 
            WHERE list_id = :list_id AND movie_id = :movie_id
            LIMIT 1";

        $result = $this->db->query($sql, [
            'list_id' => $listId,
            'movie_id' => $movieId
        ]);

        return !empty($result);
    }
}
