<?php

namespace Mikelnavarro\Eurofilm\core;

class Service
{


    private $modelo;





    // Metodo para instanciar modelos
    public function modelo(string $modelo)
    {
        // Normaliza: "articulo" -> "Articulo"
        $modeloClase = ucfirst(strtolower(trim($modelo)));
        $fqcn = "Mikelnavarro\\Eurofilm\\models\\" . $modeloClase;


        if (!class_exists($fqcn)) {
            throw new \RuntimeException("Modelo no encontrado: $fqcn");
        }

        return new $fqcn();
    }

}