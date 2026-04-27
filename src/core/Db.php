<?php
namespace Mikelnavarro\Eurofilm\Core;

use PDO;
use PDOException;

class Db {
    private $host;
    private $usuario;
    private $password;
    private $nombre_db;

    private $dbh; // database handler
    private $stmt;
    private $error;

    public function __construct()
    {
        // Cargamos el array desde el archivo de configuración
        $config = require __DIR__ . '/../config/config.php';

        $this->host      = $config['db']['host'];
        $this->usuario   = $config['db']['user'];
        $this->password  = $config['db']['pass'];
        $this->nombre_db = $config['db']['dbname'];

        // Configurar conexión
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->nombre_db;
        $opciones = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Crear instancia de PDO
        try {
            $this->dbh = new PDO($dsn, $this->usuario, $this->password, $opciones);
            // Para usar caracteres especiales y símbolos.
            $this->dbh->exec('set names utf8mb4'); // utf8mb4 es más completo para emojis/caracteres modernos
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Error en la conexión a la base de datos: " . $this->error);
        }
    }

    // Transacciones Db
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function commit() {
        return $this->dbh->commit();
    }

    public function rollBack() {
        return $this->dbh->rollBack();
    }

    // Preparamos la consulta
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Vinculamos parámetros de la consulta
    public function bind($parametro, $valor, $tipo = null) {
        if (is_null($tipo)) {
            switch (true) {
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                    break;
                default:
                    $tipo = PDO::PARAM_STR;
                    break;
            }
        }
        $this->stmt->bindValue($parametro, $valor, $tipo);
    }

    // Ejecutamos la consulta
    public function execute() {
        return $this->stmt->execute();
    }

    // Obtenemos cantidad de filas con rowCount
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function registros(int $fetchMode = PDO::FETCH_OBJ) {
        $this->execute();
        return $this->stmt->fetchAll($fetchMode);
    }

    public function registro(int $fetchMode = PDO::FETCH_OBJ) {
        $this->execute();
        $row = $this->stmt->fetch($fetchMode);
        return $row ?: null;
    }

    public function registrosObj(): array {
        return $this->registros(PDO::FETCH_OBJ);
    }

    public function registroObj(): ?object {
        return $this->registro(PDO::FETCH_OBJ);
    }

    public function registrosAssoc(): array {
        return $this->registros(PDO::FETCH_ASSOC);
    }

    public function registroAssoc(): ?array {
        return $this->registro(PDO::FETCH_ASSOC);
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}