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
        <div>
            <div class="logo-register">
                <img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo">            
            </div>
            <h1>Black Arms</h1>
        </div>
        <p>Registra't per començar!</p>

        <?php if (isset($error)) : ?>
            <p><?php echo htmlspecialchars($error);?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="register-user">
                <label for="username">Nom d'usuari (Obligatori)</label><br>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="register-email">
                <label for="email">Correu electrònic (Obligatori)</label><br>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="register-fname">
                <label for="first_name">Nom (Opcional)</label><br>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name">
            </div>
            <div class="register-lname">
                <label for="last_name">Cognom (Opcional)</label><br>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name">
            </div>
            <div class="register-password">
                <label for="password">Contrassenya (Obligatori)</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="register-vpassword">
                <label for="verify_password">Verificar contrassenya (Obligatori)</label><br>
                <input type="password" id="verify_password" name="verify_password" required>
            </div>
            <button type="submit">Registrar</button>
        </form>

        <div>
            <p>Ja estàs registrat? <a href="index.php"">Inicia sessió!</a></p>
        </div>
    </div>
</body>
</html>
