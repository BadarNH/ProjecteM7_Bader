<?php
// db_functions.php
require_once 'db_config.php';

// Función para verificar si el usuario existe (correo o nombre de usuario)
function checkUserExists($input) {
    global $db;
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        // Buscar por email
        $stmt = $db->prepare("SELECT userID, nomUsuari, passHash, active FROM USUARI WHERE eMail = :input");
    }
    else
        $stmt = $db->prepare("SELECT userID, nomUsuari, passHash, active FROM USUARI WHERE nomUsuari = :input");
        // Buscar por nombre de usuario
        

    $stmt->bindParam(':input', $input);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el primer resultado o false
}

// Función para actualizar el estado del usuario (activar cuenta)
function activateUser($userID) {
    global $db;
    $stmt = $db->prepare("UPDATE USUARI SET active = 1 WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
}

// Función para registrar un nuevo usuario
function registerUser($username, $email, $password, $firstName = '', $lastName = '') {
    global $db;
    $passHash = password_hash($password, PASSWORD_BCRYPT);
    $createDate = date('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO USUARI (nomUsuari, eMail, passHash, userNom, userCognom, createDate, active) 
                        VALUES (:username, :email, :passHash, :firstName, :lastName, :createDate, 0)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passHash', $passHash);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':createDate', $createDate);
    
    return $stmt->execute(); // Devuelve true si se inserta correctamente
}
?>
