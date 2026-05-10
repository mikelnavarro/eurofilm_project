<?php

namespace Mikelnavarro\Eurofilm\models;

use Mikelnavarro\Eurofilm\core\Db;

class Usuario
{
    protected $db;
    public function __construct()
    {
        $this->db = new Db();
    }


    public function registrar($nombre, $username, $email, $pass, $role)
    {
        $passHash = password_hash($pass, PASSWORD_BCRYPT);


        $sql = "INSERT INTO users (name, username, email, passwordHash, role) VALUES (:name, :username, :email, :password, :role)";
        $this->db->query($sql);
        $this->db->bind(':name', $nombre);
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':password',   $passHash);
        $this->db->bind(':role',   $role);
        return $this->db->execute();
    }
    // Funciones
    public function obtenerUsuarioPorUserName($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $this->db->query($sql);
        $this->db->bind("username", $username);

        // registro() devuelve el objeto con las propiedades
        return $this->db->registros();
    }
    public function obtenerUsuarioPorEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind("email", $email);

        // registro() devuelve el objeto con las propiedades
        return $this->db->registros();
    }
    public function login($email, $clave)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND password_hash = :clave";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $this->db->bind(':clave', $clave);
        return $this->db->execute();
    }
    public function comprobar($email, $clave)
    {
        // 1. Buscamos al usuario solo por email
        $sql = "SELECT * FROM users WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':email', $email);

        // Suponiendo que tu método registro() devuelve una fila única
        $fila = $this->db->registro();
        if ($fila) {
            // 2. Extraemos el hash de la base de datos (asegúrate que el nombre de la columna sea correcto)
            $hashed_password = $fila->password;
            // 3. Verificamos si la clave coincide con el hash
            if (password_verify($clave, $hashed_password)) {
                return $fila; // Retornamos el objeto usuario si es correcto
            } else {
                return false;
            }
        } else {
            return false; // El email no existe
        }
    }
    public function updateProfile($id, $username, $email, $country, $nombre, $bio)
    {
        $sql = "UPDATE users 
            SET username = :username,
                email = :email,
                country = :country,
                name = :name
                bio = :bio
            WHERE id = :id";

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':country', $country);
        $this->db->bind(':name', $nombre);
        $this->db->bind(':bio', $bio);


        return $this->db->execute();
    }
    public function eliminarUsuario($email)
    {
        $sql = "DELETE FROM users WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $this->db->execute();
    }
}
