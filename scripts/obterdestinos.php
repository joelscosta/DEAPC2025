<?php

header('Content-Type: application/json'); // Define o cabeçalho para JSON
$databaseFile = 'database.sqlite';

// Permite requisições de origens diferentes (CORS) - ajuste para produção
header('Access-Control-Allow-Origin: *'); // Mude '*' para o seu domínio em produção (ex: 'http://seu-site.com')
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->query("SELECT id, name, description, image_url FROM destinations");
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $destinations]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro ao buscar destinos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use GET.']);
}

?>