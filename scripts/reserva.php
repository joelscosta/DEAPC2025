<?php
session_start(); // Inicia a sessão para aceder ao user_id

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';

header('Access-Control-Allow-Origin: *'); // Mude '*' para o seu domínio em produção
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o utilizador está logado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Por favor, faça login.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);

    $destinationId = $input['destination_id'] ?? null;
    $travelDate = $input['travel_date'] ?? null;
    $numTravelers = $input['num_travelers'] ?? 1;

    if (empty($destinationId) || empty($travelDate)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, forneça o ID do destino e a data da viagem.']);
        exit;
    }

    // Validação básica da data (pode ser mais robusta)
    if (!DateTime::createFromFormat('Y-m-d', $travelDate)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato de data de viagem inválido (AAAA-MM-DD).']);
        exit;
    }

    $bookingDate = date('Y-m-d'); // Data atual da reserva

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Opcional: Verificar se o destination_id existe
        $stmtCheckDest = $db->prepare("SELECT id FROM destinations WHERE id = :destination_id");
        $stmtCheckDest->bindParam(':destination_id', $destinationId, PDO::PARAM_INT);
        $stmtCheckDest->execute();
        if (!$stmtCheckDest->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Destino não encontrado.']);
            exit;
        }

        $stmt = $db->prepare("INSERT INTO bookings (user_id, destination_id, booking_date, travel_date, num_travelers) VALUES (:user_id, :destination_id, :booking_date, :travel_date, :num_travelers)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':destination_id', $destinationId, PDO::PARAM_INT);
        $stmt->bindParam(':booking_date', $bookingDate);
        $stmt->bindParam(':travel_date', $travelDate);
        $stmt->bindParam(':num_travelers', $numTravelers, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Reserva criada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao criar reserva.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro no servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use POST para criar reserva.']);
}

?>