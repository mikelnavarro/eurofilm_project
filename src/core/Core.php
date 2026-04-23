<?php

namespace Mikelnavarro\Eurofilm\core;

class Core
{
    private string $controller = 'MovieController';
    private string $method = 'index';
    private array $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        // 1. Controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = __DIR__ . '/../Controllers/' . $controllerName . '.php';

            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Cargar controller
        $controllerClass = "Mikelnavarro\\Eurofilm\\Controllers\\" . $this->controller;
        $this->controller = new Controller();

        // 2. Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Parámetros
        $this->params = $url ? array_values($url) : [];

        // Ejecutar
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Obtener URL limpia
     */
    private function getUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }
}