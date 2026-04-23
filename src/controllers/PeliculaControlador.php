<?php
namespace Mikelnavarro\Eurofilm\Controllers;
use Mikelnavarro\Eurofilm\Services\TmdbService;
class PeliculaControlador
{

// Atributos
private $tmdbService;

    public function __construct($config) {
        $this->tmdbService = new TmdbService($config['tmdb']);
    }
}
