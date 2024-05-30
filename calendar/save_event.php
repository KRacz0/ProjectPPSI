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

$title = $data['title'];
$start = $data['start'];
$end = $data['end'];
$color = $data['color'];
$allDay = $data['allDay'] ? 1 : 0;

$sql = "INSERT INTO events (user_id, event_title, event_date, event_end_date, event_color, all_day) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('prepare() failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("issssi", $user_id, $title, $start, $end, $color, $allDay);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
