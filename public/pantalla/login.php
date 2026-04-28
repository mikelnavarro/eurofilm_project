<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Eurofilm</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../inc/header.php'; ?>
<main class="login-container">
    <form id="loginForm">
        <h2>Iniciar Sesión</h2>

        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div id="errorMessage" style="color: red; display: none;"></div>

        <button type="submit" id="btnLogin">Entrar</button>
        <p>¿No tienes cuenta? <a href="registrarse.php">Regístrate aquí</a></p>
    </form>
</main>
</body>
</html>