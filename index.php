<?php
session_start();
require_once 'db_functions.php'; 
if (isset($_SESSION['usuari'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['username_or_email'];
    $password = $_POST['password'];

    
    $user = checkUserExists($input);

    if ($user) {
        $userID = $user['userID'];
        $nomUsuari = $user['nomUsuari'];
        $passHash = $user['passHash'];
        $active = $user['active'];

        if (password_verify($password, $passHash)) {
            $_SESSION['usuari'] = $nomUsuari;
            $_SESSION['userID'] = $userID;

            activateUser($userID);

            header('Location: home.php');
            exit();
        } else {
            $error = "Contrasenya incorrecta.";
        }
    } else {
        $error = "Usuari o correu electronic incorrecte o inexistent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Arms - Login</title>
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
        <p>Benvingut! Inicia sessió!</p>

        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="form-group">
                <label for="username_or_email">Usuari o Correu Electrònic</label><br>
                <input type="text" id="username_or_email" name="username_or_email" placeholder="Enter your username or email" required>
            </div>
            <div class="form-group">
                <label for="password">Contrassenya</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>

        <div style="margin-top: 1rem;">
            <p>No estas enregistrat? <a href="register.php" style="text-decoration: none; color: blue;">Uneix-te!</a></p>
        </div>

    </div>
</body>
</html>
