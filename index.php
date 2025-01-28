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
        <p>Benvingut! Inicia sessio!</p>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Usuari</label><br>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>
    </div>
</body>
</html>
