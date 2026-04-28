<?php

namespace Mikelnavarro\Eurofilm\Core;

class Controller
{


    // Metodos JSON

    /**
     * Lee y decodifica el cuerpo JSON de la petición (Fetch API).
     */
    public function getJSON()
    {
        $json = file_get_contents('php://input');

        // Lo transformamos en un array asociativo de PHP
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null; // O devuelve un error si el JSON está mal formado
        }

        return $data;
    }
    public function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }


    /**
     * Instancia y devuelve un Servicio de forma dinámica.
     */
    public function service(string $serviceName)
    {
        $fqcn = "Mikelnavarro\\Eurofilm\\services\\" . ucfirst($serviceName);

        if (!class_exists($fqcn)) {
            $this->jsonResponse(['error' => "Servicio '$serviceName' no encontrado."], 500);
        }

        return new $fqcn();
    }

    public function modelo(string $modeloName)
    {
        // Normaliza: "articulo" -> "Articulo"
        $modeloClase = ucfirst(strtolower(trim($modeloName)));
        $fqcn = "Mikelnavarro\\Eurofilm\\models\\" . ucfirst($modeloName);

        // autoload
        if (!class_exists($fqcn)) {
            throw new \RuntimeException("Modelo no encontrado: $fqcn");
        }

        return new $fqcn();
    }
    public function isLogged(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
}
