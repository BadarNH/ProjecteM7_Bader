<?php
require_once 'db_config.php';
require_once 'db_functions.php';

$error = "";
$success = "";

if (!isset($_GET['code']) || !isset($_GET['mail'])) {
    die("Falten paràmetres.");
}

$code = $_GET['code'];
$email = $_GET['mail'];

// Buscar si el código de restablecimiento existe y si no ha expirado
$stmt = $db->prepare("SELECT resetPassExpiry FROM USUARI WHERE eMail = :email AND resetPassCode = :code");
$stmt->execute([':email' => $email, ':code' => $code]);
$user = $stmt->fetch();

if (!$user) {
    $error = "Codi de restabliment invàlid.";
} elseif (strtotime($user['resetPassExpiry']) < time()) {
    $error = "El codi de restabliment ha expirat.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        $error = "Les contrasenyes no coincideixen.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "La contrasenya ha de tenir almenys 8 caràcters, una majúscula, un número i un símbol.";
    }

    if (!$error) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE USUARI SET passHash = :pass, resetPassCode = NULL, resetPassExpiry = NULL WHERE eMail = :email");
        $stmt->execute([':pass' => $passwordHash, ':email' => $email]);
    
        $success = "Contrasenya canviada amb èxit!";
    
        header("Location: home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablir Contrasenya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Restablir Contrasenya</h3>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php else: ?>
                            <!-- Solo mostrar el formulario si el código no ha expirado -->
                            <?php if (strtotime($user['resetPassExpiry']) >= time()): ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Nova Contrasenya</label>
                                        <input type="password" class="form-control <?= $error ? 'is-invalid' : '' ?>" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirma la Contrasenya</label>
                                        <input type="password" class="form-control <?= $error ? 'is-invalid' : '' ?>" id="confirmPassword" name="confirmPassword" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Canviar Contrasenya</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">El codi de restabliment ha expirat. Si ho desitges, sol·licita un nou codi.</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    body {        
        background-color: #d1d1d1;
    }
</style>
</html>
