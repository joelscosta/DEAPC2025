<?php
session_start(); // Inicia a sessão para aceder ao user_id

header('Content-Type: application/json');
$databaseFile = 'database.sqlite';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se o utilizador está logado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Por favor, faça login.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $currentDate = date('Y-m-d'); // Data atual para comparação

    try {
        $db = new PDO('sqlite:' . $databaseFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Junta as tabelas bookings, destinations e users para obter todos os detalhes
        $stmt = $db->prepare("
            SELECT
                b.id AS booking_id,
                b.booking_date,
                b.travel_date,
                b.num_travelers,
                b.status,
                d.name AS destination_name,
                d.description AS destination_description,
                d.image_url AS destination_image_url,
                u.username AS user_username
            FROM
                bookings b
            JOIN
                destinations d ON b.destination_id = d.id
            JOIN
                users u ON b.user_id = u.id
            WHERE
                b.user_id = :user_id
            ORDER BY
                b.travel_date DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $allBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pastBookings = [];
        $futureBookings = [];

        foreach ($allBookings as $booking) {
            if ($booking['travel_date'] < $currentDate) {
                $pastBookings[] = $booking;
            } else {
                $futureBookings[] = $booking;
            }
        }

        echo json_encode([
            'status' => 'success',
            'past_bookings' => $pastBookings,
            'future_bookings' => $futureBookings
        ]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => "Erro ao buscar reservas: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido. Use GET.']);
}

?>