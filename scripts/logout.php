<?php
session_start(); // Inicia a sessão

header('Content-Type: application/json');

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Se for usada a sessão baseada em cookies, destrói o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão
session_destroy();

echo json_encode(['status' => 'success', 'message' => 'Logout efetuado com sucesso.']);
exit;
?>