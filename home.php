<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuari'])) {
    header('Location: index.php');
    exit();
}

try {
    // Conexión a la base de datos
    $cadena_connexio = 'mysql:dbname=redsocialdb;host=localhost:3335';
    $usuari = 'root';
    $passwd = '';
    try {
        $db = new PDO($cadena_connexio, $usuari, $passwd, array(PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {
        echo 'Error al conectar con la base de datos: ' . $e->getMessage();
        exit();
    }

    // Si se envió un nuevo post
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_post'])) {
        $newPost = $_POST['new_post'];

        // Insertar el nuevo post en la base de datos
        $stmt = $db->prepare("INSERT INTO PUBLICACIO (userID, post, datePubisehd) VALUES (:userID, :post, NOW())");
        $stmt->bindParam(':userID', $_SESSION['userID']);
        $stmt->bindParam(':post', $newPost);
        $stmt->execute();
    }

    // Obtener los posts de la base de datos
    $stmt = $db->prepare("SELECT p.idPost, p.post, p.datePubisehd, u.nomUsuari FROM PUBLICACIO p
                        JOIN USUARI u ON p.userID = u.userID ORDER BY p.datePubisehd DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Arms - Home</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="container">
        
        <div class="menu">
            <a href="home.php"><img src="Black_Arms_Symbol.jpg" alt="Black Arms Logo" style="max-width: 200px; height: auto;"></a>
            <div class="menu-items">
                <a href="home.php">Inici</a>
                <a href="logout.php">Tancar sessió</a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <h1>Benvingut, <?php echo htmlspecialchars($_SESSION['usuari']); ?>!</h1>

            <!-- Formulario para crear un nuevo post -->
            <div class="new-post">
                <form action="home.php" method="post">
                    <textarea name="new_post" rows="4" placeholder="Escriu el teu post..." required></textarea><br>
                    <button type="submit">Publicar</button>
                </form>
            </div>

            <!-- Posts -->
            <div class="posts">
                <?php foreach ($posts as $post) : ?>
                    <div class="post">
                        <p><strong><?php echo htmlspecialchars($post['nomUsuari']); ?></strong> <span><?php echo date('d-m-Y H:i', strtotime($post['datePubisehd'])); ?></span></p>
                        <p><?php echo nl2br(htmlspecialchars($post['post'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
