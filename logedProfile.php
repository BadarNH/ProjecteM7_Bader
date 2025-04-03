<?php
session_start();
require_once 'db_config.php';
require_once 'db_functions.php';

if (!isset($_SESSION['usuari'])) {
    header('Location: index.php');
    exit();
}

$user = checkUserExists($_SESSION['usuari']);
if (!$user) {
    echo "Error: Usuario no encontrado.";
    exit();
}

$userID = $user['userID'];
$stmt = $db->prepare("SELECT profilePic, bio, ubication, birth FROM USUARI WHERE userID = :userID");
$stmt->bindParam(':userID', $userID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Perfil - <?php echo htmlspecialchars($_SESSION['usuari']); ?></title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <div class="menu-home">
            <a href="home.php"><img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo"></a>
            <div class="menu-home-items">
                <a href="home.php">Inici</a>
                <a href="logout.php">Tancar sessió</a>
            </div>
        </div>

        <div class="profile">
            <img src="<?php echo $userData['profilePic'] ?: 'default-profile.png'; ?>" alt="Foto de perfil" class="profile-pic">
            <h1><?php echo htmlspecialchars($_SESSION['usuari']); ?></h1>
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
            <p><strong><br></strong> <?php echo nl2br(htmlspecialchars($userData['bio'] ?: 'No hi ha biografia.')); ?></p>

            <a href="editProfile.php" class="btn-edit-profile">Editar perfil</a>
        </div>


        <div class="posts">
            <h2>Les teves publicacions</h2>
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
