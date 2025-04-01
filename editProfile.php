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

// Actualizar perfil cuando se recibe el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newBio = $_POST['bio'];
    $newUbication = $_POST['ubication'];
    $newBirth = $_POST['birth'];
    $newProfilePic = $userData['profilePic']; // Mantener la foto actual si no se sube una nueva

    // Manejar la carga de la nueva foto de perfil
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $fileTmpPath = $_FILES['profilePic']['tmp_name'];
        $fileName = $_FILES['profilePic']['name'];
        $fileSize = $_FILES['profilePic']['size'];
        $fileType = $_FILES['profilePic']['type'];

        // Verificar tipo de archivo (solo imágenes)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Crear un nombre único para la imagen
            $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
            $uploadDir = 'uploads/profile_pics/'; // Directorio donde se almacenará la foto

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Crear el directorio si no existe
            }

            // Mover el archivo al directorio de destino
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Si la carga fue exitosa, actualizar la ruta de la foto en la base de datos
                $newProfilePic = $destPath;
            } else {
                echo "Error al cargar la foto de perfil.";
                exit();
            }
        } else {
            echo "El archivo no es una imagen válida.";
            exit();
        }
        header('Location: logedProfile.php');
    }

    // Actualizar en la base de datos
    $stmt = $db->prepare("UPDATE USUARI SET bio = :bio, ubication = :ubication, birth = :birth, profilePic = :profilePic WHERE userID = :userID");
    $stmt->bindParam(':bio', $newBio);
    $stmt->bindParam(':ubication', $newUbication);
    $stmt->bindParam(':birth', $newBirth);
    $stmt->bindParam(':profilePic', $newProfilePic);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();

    echo "Perfil actualizado exitosamente.";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - <?php echo htmlspecialchars($_SESSION['usuari']); ?></title>
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

        <div class="profile-edit">
            <h1>Editar Perfil</h1>
            <form action="editProfile.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bio">Biografía</label>
                    <textarea id="bio" name="bio"><?php echo htmlspecialchars($userData['bio']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ubication">Ubicación</label>
                    <input type="text" id="ubication" name="ubication" value="<?php echo htmlspecialchars($userData['ubication']); ?>">
                </div>
                <div class="form-group">
                    <label for="birth">Fecha de nacimiento</label>
                    <input type="date" id="birth" name="birth" value="<?php echo $userData['birth'] ? date('Y-m-d', strtotime($userData['birth'])) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="profilePic">Foto de perfil</label>
                    <input type="file" id="profilePic" name="profilePic">
                </div>

                <button type="submit">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</body>
</html>
