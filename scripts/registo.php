<?php

header('Content-Type: application/json'); // Define o cabeçalho para JSON
$databaseFile = 'database.sqlite';

// Permite requisições de origens diferentes (CORS) - ajuste para produção
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responde a requisições OPTIONS (preflight CORS)
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o corpo da requisição JSON
    $input = json_decode(file_get_contents('php://input'), true);

    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos.']);
        exit;
    }

    // Hash da senha (IMPORTANTE para segurança!)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => "Usuário '$username' registrado com sucesso!"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar usuário.']);
        }

    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // SQLite unique constraint violation
            echo json_encode(['status' => 'error', 'message' => "Nome de usuário '$username' já existe. Escolha outro."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Erro: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use POST para registrar.']);
}

?>