<?php
    require_once 'db_config.php';
    require_once 'db_functions.php';

    if (isset($_GET['code']) && isset($_GET['mail'])) {
        $code = $_GET['code'];
        $email = $_GET['mail'];

        $stmt = $db->prepare("SELECT * FROM USUARI WHERE eMail = :email AND activationCode = :code");
        $stmt->execute([':email' => $email, ':code' => $code]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $db->prepare("UPDATE USUARI SET active = 1, activationCode = NULL, activationDate = NOW() WHERE eMail = :email");
            $stmt->execute([':email' => $email]);
            echo "Compte activat amb èxit!";
        } else {
            echo "Codi de verificació invàlid.";
        }
    } else {
        echo "Falten paràmetres.";
    }
?>
