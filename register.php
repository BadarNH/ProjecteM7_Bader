<?php
session_start();
require_once 'db_config.php';
require_once 'db_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $password = $_POST['password'];
    $verifyPassword = $_POST['verify_password'];

    if ($password !== $verifyPassword) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $userExists = checkUserExists($username);
        $emailExists = checkUserExists($email);

        if ($userExists || $emailExists) {
            $error = "El nombre de usuario o el correo electrónico ya está en uso.";
        } else {
            try {
                $registerSuccess = registerUser($username, $email, $password, $firstName, $lastName);
                
                if ($registerSuccess) {
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "Hubo un problema al registrar el usuario. Inténtalo de nuevo.";
                }
            } catch (PDOException $e) {
                $error = "Error en el registro: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Arms - Register</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
            <div class="logo" style="margin-right: 15px;">
                <img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo" style="max-width: 80px; height: auto;">            
            </div>
            <h1 style="margin: 0; font-size: 2rem;">Black Arms</h1>
        </div>
        <p>Registra't per començar!</p>

        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'usuari (Obligatori)</label><br>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="email">Correu electrònic (Obligatori)</label><br>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="first_name">Nom (Opcional)</label><br>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name">
            </div>
            <div class="form-group">
                <label for="last_name">Cognom (Opcional)</label><br>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name">
            </div>
            <div class="form-group">
                <label for="password">Contrassenya (Obligatori)</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="verify_password">Verificar contrassenya (Obligatori)</label><br>
                <input type="password" id="verify_password" name="verify_password" required>
            </div>
            <button type="submit">Registrar</button>
        </form>

        <div style="margin-top: 1rem;">
            <p>Ja estàs registrat? <a href="index.php" style="text-decoration: none; color: blue;">Inicia sessió!</a></p>
        </div>
    </div>
</body>
</html>
