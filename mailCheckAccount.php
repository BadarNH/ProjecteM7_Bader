<?php
    require_once 'db_config.php';
    require_once 'db_functions.php';

    if (isset($_GET['code']) && isset($_GET['mail'])) {
        $code = $_GET['code'];
        $email = $_GET['mail'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :code");
        $stmt->execute(['email' => $email, 'code' => $code]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET verified = 1, verification_code = NULL WHERE email = :email");
            $stmt->execute(['email' => $email]);
            echo "Compte activat amb èxit!";
        } else {
            echo "Codi de verificació invàlid.";
        }
    } else {
        echo "Falten paràmetres.";
    }
?>
