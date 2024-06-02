<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="style.css">
    <title>Kalendarz</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Twój kalendarz</h1>
                <div id="calendar" class="mt-4"></div>
            </div>
        </div>
    </div>
    
    <!-- FORMULARZ DODAWANIA NOWEGO WYDARZENIA -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Dodaj Wydarzenie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Tytuł Wydarzenia</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventStartDate" class="form-label">Data Początkowa</label>
                            <input type="date" class="form-control" id="eventStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventEndDate" class="form-label">Data Końcowa</label>
                            <input type="date" class="form-control" id="eventEndDate" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="allDayEvent" checked>
                            <label class="form-check-label" for="allDayEvent">Cały Dzień</label>
                        </div>
                        <div id="timeFields" class="mb-3" style="display: none;">
                            <div class="mb-3">
                                <label for="eventStartTime" class="form-label">Godzina Początkowa</label>
                                <input type="time" class="form-control" id="eventStartTime">
                            </div>
                            <div class="mb-3">
                                <label for="eventEndTime" class="form-label">Godzina Końcowa</label>
                                <input type="time" class="form-control" id="eventEndTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="eventColor" class="form-label">Kolor</label>
                            <input type="color" class="form-control" id="eventColor" value="#378006">
                        </div>
                        <div class="mb-3">
                            <label for="eventNote" class="form-label">Notatka</label>
                            <textarea class="form-control" id="eventNote" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULARZ EDYTOWANIA WYDARZENIA -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edytuj Wydarzenie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" id="editEventId">
                        <div class="mb-3">
                            <label for="editEventTitle" class="form-label">Tytuł Wydarzenia</label>
                            <input type="text" class="form-control" id="editEventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventStartDate" class="form-label">Data Początkowa</label>
                            <input type="date" class="form-control" id="editEventStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventEndDate" class="form-label">Data Końcowa</label>
                            <input type="date" class="form-control" id="editEventEndDate" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="editAllDayEvent">
                            <label class="form-check-label" for="editAllDayEvent">Cały Dzień</label>
                        </div>
                        <div id="editTimeFields" class="mb-3" style="display: none;">
                            <div class="mb-3">
                                <label for="editEventStartTime" class="form-label">Godzina Początkowa</label>
                                <input type="time" class="form-control" id="editEventStartTime">
                            </div>
                            <div class="mb-3">
                                <label for="editEventEndTime" class="form-label">Godzina Końcowa</label>
                                <input type="time" class="form-control" id="editEventEndTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editEventColor" class="form-label">Kolor</label>
                            <input type="color" class="form-control" id="editEventColor" value="#378006">
                        </div>
                        <div class="mb-3">
                            <label for="editEventNote" class="form-label">Notatka</label>
                            <textarea class="form-control" id="editEventNote" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                        <button type="button" id="deleteEventButton" class="btn btn-danger">Usuń</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/locales/pl.min.js'></script>
    <script src='script.js'></script>
</body>
</html>
