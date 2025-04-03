<?php
// Incluir la configuración de conexión a la base de datos
include 'config.php'; // Asegúrate de que este archivo contiene la configuración correcta de la base de datos

if (isset($_GET['idPost'])) {
    $idPost = $_GET['idPost'];

    try {
        // Conexión a la base de datos
        $db = new PDO($cadena_connexio, $usuari, $passwd, array(PDO::ATTR_PERSISTENT => true));

        // Obtener el post
        $stmt = $db->prepare("SELECT post FROM PUBLICACIO WHERE idPost = :idPost");
        $stmt->bindParam(':idPost', $idPost);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener los comentarios ordenados por likes
        $stmt = $db->prepare("SELECT c.comentario, c.likes, u.nomUsuari FROM COMENTARIO c
                                JOIN USUARI u ON c.userID = u.userID
                                WHERE c.idPost = :idPost
                                ORDER BY c.likes DESC");
        $stmt->bindParam(':idPost', $idPost);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Responder con los datos en formato JSON
        echo json_encode([
            'postTitle' => htmlspecialchars($post['post']),
            'postContent' => nl2br(htmlspecialchars($post['post'])),
            'comments' => $comments
        ]);
    } catch (PDOException $e) {
        echo "Error en la conexión a la base de datos: " . $e->getMessage();
        exit();
    }
}
?>
