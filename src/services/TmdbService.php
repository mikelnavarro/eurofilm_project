<?php

namespace Mikelnavarro\Eurofilm\services;

class TmdbService {

// Atributos
    private $token;
    private $baseUrl;
    private $config;

    // Constructores
    public function __construct() {
        $config = require __DIR__ . '/../config/config.php';
        $this->token  = $config['tmdb']['token'];
        $this->baseUrl = $config['tmdb']['base_url'];
    }


    public function consultar($endpoint, $params = []) {
        $params['language'] = 'es-ES';

        // Construimos la URL: base + endpoint + ?query_string
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);


        $ch = curl_init();

        // Esto enviará el log de la conexión a un archivo temporal para que lo veas
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


        // CONFIGURACIÓN PARA EL BEARER TOKEN
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);

        $resultado = curl_exec($ch);
        // Si sigue fallando, esto te dirá por qué exactamente
        if (curl_errno($ch)) {
            echo 'Error de cURL: ' . curl_error($ch);
        }
        curl_close($ch);
        // Decodificamos a array asociativo (NO volveemos a encodificar)
        return json_decode($resultado, true);
    }
}

