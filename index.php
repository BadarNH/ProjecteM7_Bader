<?php
session_start();
require_once 'db_functions.php'; 
if (isset($_SESSION['usuari'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username_or_email'])) {
        // Login
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
                $error = ($active != 1) ? "Compte pendent de verificar" : "Contrasenya incorrecta.";
            }
        } else {
            $error = "Usuari o correu electrònic incorrecte o inexistent.";
        }
    } 
    else if(isset($_POST['restoreInput']))
    {
        $input = $_POST['restoreInput'];
        $user = checkUserExists($input);
        if($user)
        {
            if (!$user) {
                $error = "Compte inexistent";
            } 
            else{
                if(!str_contains($user, '@'))
                {
                    $user = getEmailByUser($user);
                }

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
                    $mail->Subject = 'Recuperació de contrasenya';
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
    <title>Black Arms - Login</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js" defer></script>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Contrasenya olvidada?</a>            
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Recuperar Contrasenya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="restoreInput" class="col-form-label">Correu o usuari:</label>
                                <input type="text" class="form-control" id="restoreInput" name="restoreInput" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>   

    </div>
</body>
</html>
