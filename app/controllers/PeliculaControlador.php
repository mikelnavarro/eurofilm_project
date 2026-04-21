<?php
class PeliculaControlador {
    public function guardar() {
        // Leemos el JSON que envió JS
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);

        if (isset($datos['id_tmdb'])) {
            // Llamamos al Modelo
            $modelo = new Pelicula();
            $exito = $modelo->insertarFavorito($datos['id_tmdb'], 1); // El 1 es el ID usuario

            echo json_encode(["mensaje" => "Guardado en DB con éxito", "status" => "ok"]);
        }
    }
}