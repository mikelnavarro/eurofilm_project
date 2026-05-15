<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/profile.css">
    <script defer src="../js/verPerfil.js" type="module"></script>
    <script defer src="../js/updatePerfil.js" type="module"></script>
</head>

<body>
    <?php include_once '../inc/header.php' ?>
    <main>
            <?php
            if (!isset($_SESSION['usuario'])) {
                echo "No estás logueado";
                exit;
            }

            $usuario = $_SESSION['usuario'];
            ?>

            <h2>Perfil</h2>

            <div class="perfil-container">
                <p><strong>ALTA:</strong> <?php echo $usuario['fecha_alta'] ?? 'No definido';?></p>
                <p><strong>Nombre:</strong> <?php echo $usuario['nombre'] ?? 'No definido'; ?></p>
                <p><strong>Username:</strong> <?php echo $usuario['username'] ?? 'No definido'; ?></p>
                <form id="form-profile">
                    <h2>Actualiza tu Usuario</h2><br>
                    <label>Name</label>
                    <input id="nombre" value="<?php echo $usuario['nombre'] ?? 'No definido'; ?>" name="nombre" disabled><br><br>

                    <label>Username</label>
                    <input id="username" value="<?php echo $usuario['username'] ?? 'No definido'; ?>" name="username" disabled><br><br>

                    <label>Email</label>
                    <input id="email" value="<?php echo $usuario['email'] ?? 'No definido'; ?>" name="email" disabled><br><br>

                    <label>Country</label>
                    <input id="country" value="" name="country" disabled><br><br>

                    <label>Bio</label>
                    <textarea id="bio" value="" name="bio" disabled></textarea><br><br>

                    <button type="button" id="btn-edit">Editar</button>
                    <button type="submit" id="btn-save">Guardar</button><br><br>
                </form>
            </div>
        <?php include 'reviews-user.php'; ?>
        <?php include 'favoritos.php'; ?>

    </main>
    <?php include_once '../inc/footer.php' ?>

</body>

</html>