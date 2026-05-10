<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/global.css">
    <script defer src="../js/verPerfil.js" type="module"></script>
</head>

<body>
    <?php include_once '../inc/header.php' ?>
    <main>
        <div>
            <?php
            if (!isset($_SESSION['usuario'])) {
                echo "No estás logueado";
                exit;
            }

            $usuario = $_SESSION['usuario'];
            ?>

            <h2>Perfil</h2>
            <?php var_dump($_SESSION['usuario']); ?>
            <h1>Perfil de usuario</h1>

            <div class="perfil-container">

                <p><strong>Username:</strong> <?php echo $usuario['username'] ?? 'No definido'; ?></p>

                <p><strong>Email:</strong> <?php echo $usuario['email'] ?? 'No definido'; ?></p>

            </div>
            
            <form id="form-profile">
                <label>Username</label>
                <input id="username" name="username" disabled><br><br>

                <label>Email</label>
                <input id="email" name="email" disabled><br><br>

                <label>Country</label>
                <input id="country" name="country" disabled><br><br>

                <button type="button" id="btn-edit">Editar</button>
                <button type="submit">Guardar</button><br><br>
            </form>
        </div>
        <?php include 'favoritos.php'; ?>

    </main>
    <?php include_once '../inc/footer.php' ?>

</body>

</html>