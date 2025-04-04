<?php
include 'config.php';

if (isset($_GET['idPost'])) {
    $idPost = $_GET['idPost'];

    try {
        
        $db = new PDO($cadena_connexio, $usuari, $passwd, array(PDO::ATTR_PERSISTENT => true));

        $stmt = $db->prepare("SELECT post FROM PUBLICACIO WHERE idPost = :idPost");
        $stmt->bindParam(':idPost', $idPost);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT c.comentario, c.likes, u.nomUsuari FROM COMENTARIO c
                                JOIN USUARI u ON c.userID = u.userID
                                WHERE c.idPost = :idPost
                                ORDER BY c.likes DESC");
        $stmt->bindParam(':idPost', $idPost);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
