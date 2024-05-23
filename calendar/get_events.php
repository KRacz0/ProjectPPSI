<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT event_date as start, event_title as title, event_description as description FROM events WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('prepare() failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute() === false) {
    die('execute() failed: ' . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

if ($result === false) {
    die('get_result() failed: ' . htmlspecialchars($stmt->error));
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
