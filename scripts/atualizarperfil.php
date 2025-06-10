<?php
session_start();

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Por favor, faça login.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);

    $fullName = $input['full_name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("UPDATE users SET full_name = :full_name, email = :email, phone = :phone WHERE id = :user_id");
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Perfil atualizado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar perfil.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro no servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use POST para atualizar o perfil.']);
}

?>