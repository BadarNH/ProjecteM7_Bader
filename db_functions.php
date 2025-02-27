<?php

require_once 'db_config.php';

function checkUserExists($input) {
    global $db;
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $stmt = $db->prepare("SELECT userID, nomUsuari, passHash, active FROM USUARI WHERE eMail = :input");
    }
    else
        $stmt = $db->prepare("SELECT userID, nomUsuari, passHash, active FROM USUARI WHERE nomUsuari = :input");
        

    $stmt->bindParam(':input', $input);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function activateUser($userID) {
    global $db;
    $stmt = $db->prepare("UPDATE USUARI SET active = 1 WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
}

function registerUser($username, $email, $password, $firstName = '', $lastName = '') {
    global $db;
    $passHash = password_hash($password, PASSWORD_BCRYPT);
    $createDate = date('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO USUARI (nomUsuari, eMail, passHash, userNom, userCognom, createDate) 
                        VALUES (:username, :email, :passHash, :firstName, :lastName, :createDate)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passHash', $passHash);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':createDate', $createDate);
    
    return $stmt->execute();
}

function saveVerificationCode($email, $code) {
    global $pdo; // Asegúrate de tener tu conexión a la base de datos
    $stmt = $pdo->prepare("UPDATE users SET verification_code = :code WHERE email = :email");
    $stmt->execute(['code' => $code, 'email' => $email]);
}

?>
