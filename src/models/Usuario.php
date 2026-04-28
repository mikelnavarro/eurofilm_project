<?php

namespace Mikelnavarro\Eurofilm\models;
use Mikelnavarro\Eurofilm\core\Db;
class Usuario
{
    protected $db;
    public function __construct() {
        $this->db = new Db();
    }


    public function registrar($nombre, $email, $pass){
        $passHash = password_hash($pass, PASSWORD_BCRYPT);


        $sql = "INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :pass)";
        $this->db->query($sql);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':email', $email);
        $this->db->bind(':pass',   $passHash);
        return $this->db->execute();
    }
    // Funciones
    public function obtenerUsuarioPorEmail($email){
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind("email", $email);

        // registro() devuelve el objeto con las propiedades
        return $this->db->registro();
    }
    public function login($email, $clave){
        $sql = "SELECT * FROM usuarios WHERE email = :email AND password_hash = :clave";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $this->db->bind(':clave', $clave);
        return $this->db->execute();
    }
    public function comprobar($email, $clave){
        // 1. Buscamos al usuario solo por email
        $sql = "SELECT * FROM usuarios WHERE email = :email";
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
    public function eliminarUsuario($email){
        $sql = "DELETE FROM usuarios WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        $this->db->execute();
    }
}