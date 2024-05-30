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
    <div class="modal" id="eventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj Wydarzenie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="form-group">
                        <label for="eventTitle">Tytuł Wydarzenia</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="eventStartDate">Data Początkowa</label>
                        <input type="date" class="form-control" id="eventStartDate" required>
                    </div>
                    <div class="form-group">
                        <label for="eventEndDate">Data Końcowa</label>
                        <input type="date" class="form-control" id="eventEndDate" required>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="allDayEvent" checked>
                        <label for="allDayEvent">Cały Dzień</label>
                    </div>
                    <div id="timeFields" style="display: none;">
                        <div class="form-group">
                            <label for="eventStartTime">Godzina Początkowa</label>
                            <input type="time" class="form-control" id="eventStartTime">
                        </div>
                        <div class="form-group">
                            <label for="eventEndTime">Godzina Końcowa</label>
                            <input type="time" class="form-control" id="eventEndTime">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Kolor</label>
                        <input type="color" class="form-control" id="eventColor" value="#378006">
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
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
