<?php

namespace Mikelnavarro\Eurofilm\controllers;

use Mikelnavarro\Eurofilm\core\Controller;

class AuthController extends Controller
{
    // Atributos
    private $usuarioModelo;
    // Constructores
    public function __construct()
    {
        $this->usuarioModelo = $this->modelo("Usuario");
    }
    // Registrase


    // Otro método 
    public function registrarse()
    {
        // Comprobamos que es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405); // Devolvemos el metodo JSON
            return;
        }

        // datos (FORMDATA)
        $nombre = $_POST['nombre'] ?? null;
        $username = $_POST['username'] ?? null;
        $email  = $_POST['email'] ?? null;
        $clave  = $_POST['password'] ?? null;
        // datos incompletos
        if (!$nombre || !$username || !$email || !$clave) {
            $this->jsonResponse(['error' => 'Datos incompletos'], 400);
            return;
        }


        // comprobar duplicado
        $usuarioIgual = $this->usuarioModelo->obtenerUsuarioPorEmail($email); // comprobamos que existe el email

        if ($usuarioIgual) {
            $this->jsonResponse(['error' => "El correo $email ya existe"], 400);
            return;
        }
        // no se va a poder meter otro con el correo igual, hay que comprobar que son distintos
        $this->usuarioModelo->registrar($nombre, $username, $email, $clave);

        $usuario = $this->usuarioModelo->obtenerUsuarioPorEmail($email);

        // datos existenes
        // obtenemos

        if ($usuario) {
            $_SESSION['usuario'] = [
                'nombre' => $usuario->name,
                'username' => $usuario->username,
                'email' => $usuario->email,
                'fecha_alta' => $usuario->fecha_alta
            ];
            $this->jsonResponse(['ok' => true, 'usuario' => $_SESSION['usuario']]);
        } else {
            $this->jsonResponse(['error' => 'No se ha podido registrar'], 500);
        }
    }
    // login
    public function login()
    {

        // Comprobamoos que es metodo Post
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
            return;
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $clave = $_POST['password'];

        // comprobamos
        $usuario = $this->usuarioModelo->obtenerUsuarioPorEmail($email);
        // $usuario = $this->usuarioModelo->obtenerUsuarioPorUserName($username);
        var_dump($usuario);
        if (!$usuario || !password_verify($clave, $usuario->passwordHash)) {
            $this->jsonResponse(['error' => 'Credenciales incorrectas'], 401);
            return;
        }

        // guardamos 
        // sesión
        $_SESSION['usuario'] = [
            'nombre' => $usuario->name,
            'username' => $usuario->username,
            'email' => $usuario->email,
            'fecha_alta' => $usuario->fecha_alta
        ];
        $this->jsonResponse(['ok' => true]);
    }
    public function perfil()
    {

        if (!isset($_SESSION['usuario'])) {
            $this->jsonResponse([
                'ok' => false,
                'error' => 'No autenticado'
            ], 401);
            return;
        }


        $email = $_SESSION['usuario']['email'];

        $usuario = $this->usuarioModelo->obtenerUsuarioPorEmail($email);
        if (!$usuario) {
            $this->jsonResponse(['error' => 'Usuario no encontrado'], 404);
            return;
        }
        $this->jsonResponse([
            'ok' => true,
            'usuario' => $_SESSION['usuario']
        ]);
    }
    // destruir la sesión
    public function logout()
    {
        session_destroy();
        header("Location: /Eurofilm/public/movies/movies.php");
        exit;
    }
    // Para saber si esta logueado
    public function usuario()
    {
        if (isset($_SESSION['usuario'])) {
            $this->jsonResponse($_SESSION['usuario']);
        } else {
            $this->jsonResponse(['error' => 'No autenticado'], 401);
        }
    }
}
