<?php
session_start();

// Si l'usuari ja està loguejat, redirigeix a home.php
if (isset($_SESSION['usuari'])) {
    header('Location: home.php');
    exit();
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
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Usuari</label><br>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Contrassenya</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>

        <!-- Afegeix un botó que redirigeixi a home.php -->
        <div style="margin-top: 1.5rem;">
            <a href="home.php" class="home-btn" style="text-decoration: none;">
                <button type="button" style="padding: 10px 20px; background-color: #333; color: #fff; border: none; cursor: pointer;">
                    Go to Home
                </button>
            </a>
        </div>
    </div>
</body>
</html>
