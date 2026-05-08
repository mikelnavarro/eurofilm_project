<?php
namespace Mikelnavarro\Eurofilm\models;

use Mikelnavarro\Eurofilm\core\Db;
use PDO;
use PDOException;
class List 
{

// atributos
    private $db;


    public function __construct($db)
    {
        $this->db = $db;
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
    public function addMovieToList($listId, $tmdbId, $title, $poster, $date)
    {
        $sql = "INSERT INTO list_movies 
                (list_id, tmdb_id, title, poster_path, release_date)
                VALUES (:list_id, :tmdb_id, :title, :poster, :date)";

        return $this->db->execute($sql, [
            'list_id' => $listId,
            'tmdb_id' => $tmdbId,
            'title' => $title,
            'poster' => $poster,
            'date' => $date
        ]);
    }
}



