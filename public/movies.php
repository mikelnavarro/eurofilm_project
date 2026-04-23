<?php


header("Content-Type: application/json");
// Capturar datos de la URL
$action = $_GET['action'] ?? 'trending';
$query = $_GET['query'] ?? '';
$id = $_GET['id'] ?? '';
// Configuracion
$token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZDNmYWVjZDI3ODVkYzM1ZjE2MzVmOGQwM2Q2OWU4ZSIsIm5iZiI6MTc3NjI4MDQyMC43NTIsInN1YiI6IjY5ZGZlMzY0MTY4N2RiZGU1NmY5NDA5NyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8V7izLrCtRLetzuCAPWnWLW3aUEuvl-LSkdg8KHqhIE';
$baseUrl = "https://api.themoviedb.org/3";




switch ($action) {
    case 'search':
        $url = "$baseUrl/search/movie?query=" . urlencode($query) . "&language=es-ES";
        break;
    case 'details':
        $url = "$baseUrl/movie/$id?language=es-ES";
        break;
    case 'trending':
    default:
        $url = "$baseUrl/trending/movie/day?language=es-ES";
        break;
}

// Ejecución de cURL

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Opcional: para depurar errores
curl_close($ch);

// Respuesta de cURL
if ($httpCode !== 200) {
    echo json_encode(["error" => "Error al conectar con TMDB", "code" => $httpCode]);
} else {
    echo $response;
}



