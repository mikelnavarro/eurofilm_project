<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\core\Controller;

class UserController extends Controller
{
    // Atributos
    private $usuarioServicio;
    // Constructores
    public function __construct() {
        $this->usuarioServicio = $this->service('UserService');
    }
}