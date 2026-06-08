<?php

namespace Mikelnavarro\Eurofilm\Core;

use PDO;
use PDOException;
use RuntimeException;
use Throwable;

/**
 * Clase responsable de ejecutar migraciones pendientes de la base de datos.
 *
 * Esta clase no modifica composer.json ni necesita scripts de Composer. Solo
 * ofrece una API para que el proyecto pueda automatizar las migraciones desde
 * cualquier punto de entrada PHP, por ejemplo un comando propio, un endpoint
 * protegido o un archivo temporal de administracion.
 *
 * Funcionamiento general:
 * 1. Lee la configuracion de la base de datos desde src/config/config.php.
 * 2. Abre una conexion PDO independiente para ejecutar SQL directamente.
 * 3. Crea una tabla interna llamada "migrations" si todavia no existe.
 * 4. Busca archivos de migracion en src/databases/migrations.
 * 5. Ejecuta solo los archivos que no esten registrados como aplicados.
 * 6. Guarda cada migracion aplicada para evitar repetirla en futuras ejecuciones.
 *
 * Formatos soportados:
 * - Archivos .sql: se ejecuta el SQL contenido en el archivo.
 * - Archivos .php: el archivo debe devolver un callable, un objeto con metodo
 *   up(PDO $pdo), o el nombre de una clase que tenga metodo up(PDO $pdo).
 *
 * Convencion recomendada para nombres:
 * - 20260608180000_create_users_table.sql
 * - 20260608180500_add_role_to_users.php
 *
 * El prefijo numerico permite que el orden alfabetico sea tambien el orden de
 * ejecucion. La clase ordena los archivos por nombre antes de aplicar cambios.
 */
class Migrator
{
    /**
     * Conexion PDO usada para ejecutar migraciones y consultar el historial.
     */
    private PDO $pdo;

    /**
     * Carpeta donde se guardan los archivos de migracion.
     */
    private string $migrationsPath;

    /**
     * Nombre de la tabla que almacena el historial de migraciones aplicadas.
     */
    private string $tableName;

    /**
     * Crea el migrador.
     *
     * @param string|null $migrationsPath Ruta opcional para migraciones. Si no
     *        se indica, usa src/databases/migrations.
     * @param string $tableName Nombre de la tabla de historial.
     */
    public function __construct(?string $migrationsPath = null, string $tableName = 'migrations')
    {
        $this->migrationsPath = $migrationsPath ?? dirname(__DIR__) . '/databases/migrations';
        $this->tableName = $tableName;
        $this->pdo = $this->createConnection();
    }

    /**
     * Ejecuta todas las migraciones pendientes.
     *
     * @return array Lista con los nombres de archivo que se han aplicado.
     */
    public function run(): array
    {
        $this->ensureMigrationsTableExists();

        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();
        $pendingMigrations = array_values(array_diff($migrationFiles, $appliedMigrations));

        if ($pendingMigrations === []) {
            return [];
        }

        $batch = $this->getNextBatchNumber();
        $executed = [];

        foreach ($pendingMigrations as $migration) {
            $this->executeMigration($migration);
            $this->registerMigration($migration, $batch);
            $executed[] = $migration;
        }

        return $executed;
    }

    /**
     * Devuelve las migraciones ya aplicadas, en el mismo formato en que se
     * guardan en disco: solo el nombre del archivo, no la ruta completa.
     */
    public function applied(): array
    {
        $this->ensureMigrationsTableExists();

        return $this->getAppliedMigrations();
    }

    /**
     * Devuelve las migraciones detectadas en disco que todavia no se han
     * aplicado. Esto sirve para mostrar un resumen antes de ejecutar run().
     */
    public function pending(): array
    {
        $this->ensureMigrationsTableExists();

        return array_values(array_diff($this->getMigrationFiles(), $this->getAppliedMigrations()));
    }

    /**
     * Crea la conexion PDO usando el mismo archivo de configuracion que usa la
     * aplicacion. Se asume que las variables de entorno ya fueron cargadas antes
     * de instanciar esta clase, igual que ocurre en public/index.php.
     */
    private function createConnection(): PDO
    {
        $config = require dirname(__DIR__) . '/config/config.php';
        $db = $config['db'];

        $host = $db['host'];
        $database = $db['dbname'];
        $charset = $db['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";

        try {
            return new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo conectar a la base de datos para ejecutar migraciones.', 0, $exception);
        }
    }

    /**
     * Crea la tabla de historial si no existe.
     *
     * La columna "migration" es unica para impedir que el mismo archivo se
     * registre dos veces. "batch" agrupa las migraciones aplicadas en una misma
     * ejecucion, lo que ayuda a auditar cuando se aplicaron juntas.
     */
    private function ensureMigrationsTableExists(): void
    {
        $table = $this->quoteIdentifier($this->tableName);

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS {$table} (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                migration VARCHAR(255) NOT NULL,
                batch INT UNSIGNED NOT NULL,
                executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY migrations_migration_unique (migration)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    /**
     * Lee del historial los nombres de archivo ya aplicados.
     */
    private function getAppliedMigrations(): array
    {
        $table = $this->quoteIdentifier($this->tableName);
        $statement = $this->pdo->query("SELECT migration FROM {$table} ORDER BY migration ASC");

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Busca archivos .sql y .php en la carpeta de migraciones.
     *
     * Si la carpeta no existe, la crea. Asi la primera ejecucion deja preparada
     * la estructura sin aplicar nada.
     */
    private function getMigrationFiles(): array
    {
        if (!is_dir($this->migrationsPath) && !mkdir($this->migrationsPath, 0775, true) && !is_dir($this->migrationsPath)) {
            throw new RuntimeException("No se pudo crear la carpeta de migraciones: {$this->migrationsPath}");
        }

        $files = array_filter(scandir($this->migrationsPath) ?: [], function (string $file): bool {
            return preg_match('/\.(sql|php)$/i', $file) === 1;
        });

        sort($files, SORT_STRING);

        return array_values($files);
    }

    /**
     * Calcula el siguiente numero de lote. Si todavia no hay migraciones, el
     * primer lote sera 1.
     */
    private function getNextBatchNumber(): int
    {
        $table = $this->quoteIdentifier($this->tableName);
        $statement = $this->pdo->query("SELECT COALESCE(MAX(batch), 0) + 1 FROM {$table}");

        return (int) $statement->fetchColumn();
    }

    /**
     * Ejecuta una migracion concreta segun su extension.
     */
    private function executeMigration(string $migration): void
    {
        $path = $this->migrationsPath . DIRECTORY_SEPARATOR . $migration;
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        try {
            if ($extension === 'sql') {
                $this->executeSqlFile($path);
                return;
            }

            if ($extension === 'php') {
                $this->executePhpMigration($path);
                return;
            }
        } catch (Throwable $exception) {
            throw new RuntimeException("Error ejecutando la migracion {$migration}.", 0, $exception);
        }

        throw new RuntimeException("Formato de migracion no soportado: {$migration}");
    }

    /**
     * Ejecuta un archivo SQL.
     *
     * Se usa PDO::exec porque las migraciones suelen contener sentencias DDL
     * como CREATE TABLE o ALTER TABLE. MySQL/MariaDB puede hacer commits
     * implicitos en algunas sentencias DDL, por eso no se fuerza una transaccion
     * general alrededor de cada archivo.
     */
    private function executeSqlFile(string $path): void
    {
        $sql = file_get_contents($path);

        if ($sql === false) {
            throw new RuntimeException("No se pudo leer el archivo SQL: {$path}");
        }

        if (trim($sql) === '') {
            return;
        }

        $this->pdo->exec($sql);
    }

    /**
     * Ejecuta una migracion PHP.
     *
     * Ejemplo con callable:
     * return function (PDO $pdo): void {
     *     $pdo->exec('ALTER TABLE users ADD COLUMN active TINYINT(1) NOT NULL DEFAULT 1');
     * };
     *
     * Ejemplo con objeto:
     * return new class {
     *     public function up(PDO $pdo): void
     *     {
     *         $pdo->exec('ALTER TABLE users ADD COLUMN active TINYINT(1) NOT NULL DEFAULT 1');
     *     }
     * };
     */
    private function executePhpMigration(string $path): void
    {
        $migration = require $path;

        if (is_callable($migration)) {
            $migration($this->pdo);
            return;
        }

        if (is_string($migration) && class_exists($migration)) {
            $migration = new $migration();
        }

        if (is_object($migration) && method_exists($migration, 'up')) {
            $migration->up($this->pdo);
            return;
        }

        throw new RuntimeException("La migracion PHP debe devolver un callable, un objeto con metodo up(), o el nombre de una clase valida: {$path}");
    }

    /**
     * Registra una migracion como aplicada despues de ejecutarla correctamente.
     */
    private function registerMigration(string $migration, int $batch): void
    {
        $table = $this->quoteIdentifier($this->tableName);
        $statement = $this->pdo->prepare("INSERT INTO {$table} (migration, batch) VALUES (:migration, :batch)");
        $statement->execute([
            ':migration' => $migration,
            ':batch' => $batch,
        ]);
    }

    /**
     * Protege nombres de tablas o columnas usados como identificadores SQL.
     *
     * Los identificadores no se pueden pasar como parametros preparados, por eso
     * se validan con una expresion regular estricta antes de rodearlos con `.
     */
    private function quoteIdentifier(string $identifier): string
    {
        if (preg_match('/^[A-Za-z0-9_]+$/', $identifier) !== 1) {
            throw new RuntimeException("Identificador SQL no valido: {$identifier}");
        }

        return "`{$identifier}`";
    }
}
