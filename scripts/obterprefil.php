<?php
session_start();

// TEMPORÁRIO PARA DEPURAR:
error_log("Recebida requisição para obterperfil.php. Método: " . $_SERVER['REQUEST_METHOD']);
if (isset($_SERVER['CONTENT_TYPE'])) {
    error_log("Content-Type: " . $_SERVER['CONTENT_TYPE']);
}
error_log("Corpo da requisição: " . file_get_contents('php://input'));
// FIM DO TEMPORÁRIO

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Por favor, faça login.']);
        exit;
    }

    $userId = $_SESSION['user_id'];

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT username, full_name, email, phone FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            echo json_encode(['status' => 'success', 'data' => $userData]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Dados do utilizador não encontrados.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro no servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use GET para obter o perfil.']);
}

?>