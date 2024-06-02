<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing event ID']);
    exit();
}

$event_id = $data['id'];

$sql = "DELETE FROM events WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(['error' => 'prepare() failed: ' . htmlspecialchars($conn->error)]));
}

$stmt->bind_param("ii", $event_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
