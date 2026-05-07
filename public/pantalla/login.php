<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Eurofilm</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/auth.css">
    <script defer src="../js/login.js" type="module"></script>
</head>

<body>

    <?php include '../inc/header.php'; ?>
    <main class="auth-container">
        <form id="loginForm">
            <h1>Iniciar Sesión</h1>
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div id="login-msg" style="color: red; display: none;"></div>

            <button type="submit" id="btnLogin">Entrar</button>
            <p>¿No tienes cuenta? <a href="./register.php">Crear cuenta</a>
        </form>
    </main>
</body>

</html>