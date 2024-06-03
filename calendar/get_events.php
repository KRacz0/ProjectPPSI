<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, event_date as start, event_title as title, event_end_date as end, event_color as color, all_day as allDay, event_start_time, event_end_time, note FROM events WHERE user_id = ?";
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
    $row['allDay'] = $row['allDay'] == 1;
    if ($row['note']) {
        $row['extendedProps'] = ['note' => $row['note']];
    }
    if ($row['event_start_time']) {
        $row['start'] .= 'T' . $row['event_start_time'];
    }
    if ($row['event_end_time']) {
        $row['end'] .= 'T' . $row['event_end_time'];
    }
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
