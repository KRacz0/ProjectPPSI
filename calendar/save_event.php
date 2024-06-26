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
$note = $data['note'];

$start_time = isset($data['start']) && strpos($data['start'], 'T') !== false ? explode('T', $data['start'])[1] : null;
$end_time = isset($data['end']) && strpos($data['end'], 'T') !== false ? explode('T', $data['end'])[1] : null;

$sql = "INSERT INTO events (user_id, event_title, event_date, event_end_date, event_color, all_day, event_start_time, event_end_time, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('prepare() failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("issssisss", $user_id, $title, $start, $end, $color, $allDay, $start_time, $end_time, $note);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
