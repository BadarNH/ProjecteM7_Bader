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

        if (password_verify($password, $passHash) && $active == 1) {
            $_SESSION['usuari'] = $nomUsuari;
            $_SESSION['userID'] = $userID;

            header('Location: home.php');
            exit();
        } else {
            if($active != 1)
            {
                $error = "Compte pendednt de verificar";
            }
            else
            {
                $error = "Contrasenya incorrecta.";
            }
            
        }
    } else {
        $error = "Usuari o correu electronic incorrecte o inexistent.";
    }
}
?>

<!DOCTYPE html>
<html lang="cat">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Arms - Login</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js" defer></script>
</head>
<body>
    <div class="container-login">
        <div>
            <div class="logo-login">
                <img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo">
            </div>
            <h1>Black Arms</h1>
        </div>
        <p>Benvingut! Inicia sessió!</p>

        <?php if (isset($error)) : ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="form-user">
                <label for="username_or_email">Usuari o Correu Electrònic</label><br>
                <input type="text" id="username_or_email" name="username_or_email" placeholder="Enter your username or email" required>
            </div>
            <div class="form-password">
                <label for="password">Contrassenya</label><br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>
        <div>
            <p>No estas enregistrat? <a href="register.php">Uneix-te!</a></p>
            <p><a href="#">Contrasenya olvidada?</a></p>
        </div>            

        <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Recupera la teva contrassenya</h2>
            <input type="email" id="emailRecovery" placeholder="Introdueix el teu correu" required>
            <button id="submitRecovery">Enviar</button>
        </div>
            
    </div>
    </div>
</body>
</html>
