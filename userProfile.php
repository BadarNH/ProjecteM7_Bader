<?php
session_start();
require_once 'db_config.php';
require_once 'db_functions.php';

if (!isset($_GET['nomUsuari'])) {
    echo "Error: No se especificó un usuario.";
    exit();
}

$nomUsuari = $_GET['nomUsuari'];

$stmt = $db->prepare("SELECT userID, profilePic, bio, ubication, birth, nomUsuari FROM USUARI WHERE nomUsuari = :nomUsuari");
$stmt->bindParam(':nomUsuari', $nomUsuari);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo "Error: Usuario no encontrado.";
    exit();
}

$userID = $userData['userID'];  

$stmt = $db->prepare("SELECT post, datePubisehd FROM PUBLICACIO WHERE userID = :userID ORDER BY datePubisehd DESC");
$stmt->bindParam(':userID', $userID);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($userData['nomUsuari']); ?></title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <div class="menu-home">
            <a href="home.php"><img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo"></a>
            <div class="menu-home-items">
                <a href="logedProfile.php" class="selfProfile"><?php echo htmlspecialchars($_SESSION['usuari']); ?></a>
                <a href="home.php">Inici</a>
                <a href="logout.php">Tancar sessió</a>
            </div>
        </div>

        <div class="profile">
            <img src="<?php echo $userData['profilePic'] ?: 'default-profile.png'; ?>" alt="Foto de perfil" class="profile-pic">
            <h1><?php echo htmlspecialchars($userData['nomUsuari']); ?></h1>
            <p><strong>Ubicació:</strong> <?php echo htmlspecialchars($userData['ubication'] ?: 'No especificada'); ?></p>
            <p><strong>Edad:</strong> 
                <?php 
                    if ($userData['birth']) {
                        $birthDate = date_create($userData['birth']);
                        $today = date_create('today');
                        $age = date_diff($birthDate, $today)->y;
                        echo $age;
                    } else {
                        echo 'No especificada'; 
                    }
                ?>
            </p>
            <p><strong>Biografía:</strong> <?php echo nl2br(htmlspecialchars($userData['bio'] ?: 'No hay biografía.')); ?></p>
        </div>

        <div class="posts">
            <h2>Publicaciones de <?php echo htmlspecialchars($userData['nomUsuari']); ?></h2>
            <?php foreach ($posts as $post) : ?>
                <div class="post">
                    <p><strong><?php echo date('d-m-Y H:i', strtotime($post['datePubisehd'])); ?></strong></p>
                    <p><?php echo nl2br(htmlspecialchars($post['post'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
