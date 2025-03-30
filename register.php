<?php
session_start();
require_once 'db_config.php';
require_once 'db_functions.php';
require_once 'mailCheckAccount.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $password = $_POST['password'];
    $verifyPassword = $_POST['verify_password'];

    if ($password !== $verifyPassword) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial.";
    } else {
        $userExists = checkUserExists($username);
        $emailExists = checkUserExists($email);

        if ($userExists || $emailExists) {
            $error = "Nom d'usuari o email ja en ús";
        } else {
            try {
                $registerSuccess = registerUser($username, $email, $password, $firstName, $lastName);

                if ($registerSuccess) {
                    header('Location: index.php');
                    $randomValue = bin2hex(random_bytes(32));
                    $hashedValue = hash('sha256', $randomValue);
                    $randomValue = bin2hex(random_bytes(32));
                    $hashedValue = hash('sha256', $randomValue);

                    $domain = "http://localhost/Projecte_Fila1/ProjecteM7_Bader";
                    $verificationLink = "$domain" . "/mailCheckAccount.php?code=" . $hashedValue . "&mail=" . $email;

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPDebug = 2;
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = 'tls';

                        $mail->Port = 587;
                        $mail->Username = 'beder-eddine.maoulay-ahmedk@educem.net';
                        $mail->Password = 'amtb pbbe ngkf wlhk';

                        $mail->setFrom('beder-eddine.maoulay-ahmedk@educem.net', 'Black Arms');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Benvingut a Black Arms - Verifica el teu compte';
                        $mail->Body = "
                            <html>
                            <head>
                                <style>
                                    body { font-family: Arial, sans-serif; }
                                    .container { text-align: center; }
                                    .btn {
                                        display: inline-block;
                                        padding: 10px 20px;
                                        color: #fff;
                                        background-color: #000;
                                        text-decoration: none;
                                        border-radius: 5px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class='container'>
                                    <img src='https://drive.google.com/uc?id=11hOTVTcWHSYXyWyne7cAwniMmpWbHJin' alt='Black Arms Logo' width='100'><br>
                                    <h2>Benvingut a Black Arms</h2>
                                    <p>Gràcies per registrar-te! Fes clic a l'enllaç següent per activar el teu compte:</p>
                                    <a class='btn' href='$verificationLink'>Active your account Now!</a>
                                </div>
                            </body>
                            </html>";

                        $mail->send();
                        saveVerificationCode($email, $hashedValue);
                    } catch (Exception $e) {
                        error_log("Error al enviar el correu: " . $mail->ErrorInfo);
                    }

                    exit();
                } else {
                    $error = "Hi ha hagut un problema al registrer l'usuari. Intenta-ho un altre cop";
                }
            } catch (PDOException $e) {
                $error = "Error al registrer: " . $e->getMessage();
            }
        }
    }

}

?>

<!DOCTYPE html>
<html lang="cat">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Arms - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
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

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
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
                <label for="first_name">Nom (Obligatori)</label><br>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="register-lname">
                <label for="last_name">Cognom (Obligatori)</label><br>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
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
            <p>Ja estàs registrat? <a href="index.php">Inicia sessió!</a></p>
        </div>
    </div>
</body>
</html>
