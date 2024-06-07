<?php
session_start();
include './db_connect.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function logMessage($message) {
    $logFile = 'email_log.txt';
    $currentDate = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$currentDate] $message\n", FILE_APPEND);
}

function getEmailConfig($conn) {
    $sql = "SELECT host, port, username, password FROM email_config WHERE id = 1";
    $result = $conn->query($sql);

    if ($result === false) {
        logMessage("Database query failed: " . $conn->error);
        throw new Exception("Database query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        logMessage("Email configuration not found");
        throw new Exception("Email configuration not found");
    }
}

function sendEmail($to, $subject, $body, $emailConfig) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $emailConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig['username'];
        $mail->Password = $emailConfig['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $emailConfig['port'];

        $mail->setFrom($emailConfig['username'], 'KalendarzykDKD');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        logMessage("Message has been sent to $to");
    } catch (Exception $e) {
        logMessage("Message could not be sent to $to. Mailer Error: {$mail->ErrorInfo}");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $emailConfig = getEmailConfig($conn);

        $sql = "SELECT id, email FROM users";
        $result = $conn->query($sql);

        if ($result === false) {
            logMessage("Database query failed: " . $conn->error);
            throw new Exception("Database query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                $userId = $user['id'];
                $userEmail = $user['email'];

                $sqlEvents = "SELECT event_title, event_date, event_start_time, event_end_time, note 
                              FROM events 
                              WHERE user_id = ? 
                              AND YEARWEEK(event_date, 1) = YEARWEEK(CURDATE(), 1)";
                $stmt = $conn->prepare($sqlEvents);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $resultEvents = $stmt->get_result();

                if ($resultEvents === false) {
                    logMessage("Database query failed: " . $stmt->error);
                    throw new Exception("Database query failed: " . $stmt->error);
                }

                if ($resultEvents->num_rows > 0) {
                    $eventsList = "";
                    while ($event = $resultEvents->fetch_assoc()) {
                        $eventDate = $event['event_date'];
                        $eventStartTime = $event['event_start_time'] ? $event['event_start_time'] : 'Cały dzień';
                        $eventEndTime = $event['event_end_time'] ? ' - ' . $event['event_end_time'] : '';
                        $eventNote = $event['note'] ? '<br>Notatka: ' . $event['note'] : '';
                        $eventsList .= "<li>{$event['event_title']} - $eventDate $eventStartTime$eventEndTime $eventNote</li>";
                    }

                    $emailSubject = "Kalendarzyk - Podsumowanie tygodnia";
                    $emailBody = "<h2>Podsumowanie wydarzeń na ten tydzień:</h2><ul>$eventsList</ul>";

                    sendEmail($userEmail, $emailSubject, $emailBody, $emailConfig);
                } else {
                    logMessage("No events found for user $userEmail.");
                }

                $stmt->close();
            }
        } else {
            logMessage("No users found.");
            echo "No users found.";
        }

        echo "E-maile zostały wysłane.";
        $conn->close();
    } catch (Exception $e) {
        logMessage("Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Wyślij e-maile</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Tygodniowe podsumowanie email</h1>
        <form method="POST">
            <button type="submit" class="btn btn-primary">Wyślij e-maile</button>
        </form>
    </div>
</body>
</html>