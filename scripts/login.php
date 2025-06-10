<?php
session_start(); // Inicia a sessão PHP

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos.']);
        exit;
    }

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Guarda o ID do utilizador na sessão
                $_SESSION['username'] = $user['username']; // Guarda o username na sessão

                echo json_encode([
                    'status' => 'success',
                    'message' => "Login bem-sucedido para o utilizador: " . $user['username'],
                    'user_id' => $user['id'], // Opcional: envia o user_id para o frontend
                    'username' => $user['username']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Palavra-passe incorreta.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Utilizador não encontrado.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro no servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use POST para fazer login.']);
}

?>