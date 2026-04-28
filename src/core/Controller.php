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
    public function json($data, int $status = 200)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        // 3. Código de estado (200 OK, 201 Created, 404 Not Found, etc.)
        http_response_code($status);

        // 4. Convertimos el array de PHP a String JSON y lo enviamos
        echo json_encode($data);
        exit; // Importante para que no se ejecute nada más después
    }


    /**
     * Instancia y devuelve un Servicio de forma dinámica.
     */
    public function service(string $serviceName)
    {
        $fqcn = "Mikelnavarro\\Eurofilm\\services\\" . ucfirst($serviceName);

        if (!class_exists($fqcn)) {
            $this->json(['error' => "Servicio '$serviceName' no encontrado."], 500);
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