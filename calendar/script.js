document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    var eventForm = document.getElementById('eventForm');
    var eventTitleInput = document.getElementById('eventTitle');
    var eventStartDateInput = document.getElementById('eventStartDate');
    var eventEndDateInput = document.getElementById('eventEndDate');
    var eventStartTimeInput = document.getElementById('eventStartTime');
    var eventEndTimeInput = document.getElementById('eventEndTime');
    var eventColorInput = document.getElementById('eventColor');
    var allDayEventCheckbox = document.getElementById('allDayEvent');
    var timeFields = document.getElementById('timeFields');
    var selectedDate;

    allDayEventCheckbox.addEventListener('change', function() {
        if (allDayEventCheckbox.checked) {
            timeFields.style.display = 'none';
        } else {
            timeFields.style.display = 'block';
        }
    });

    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pl',
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Dziś',
                month: 'Miesiąc',
                week: 'Tydzień',
                day: 'Dzień'
            },
            events: 'get_events.php',
            dateClick: function(info) {
                selectedDate = info.dateStr;
                eventStartDateInput.value = selectedDate;
                eventEndDateInput.value = selectedDate;
                eventModal.show();
            },
            dayCellDidMount: function(info) {
                var addButton = document.createElement('i');
                addButton.className = 'fas fa-plus add-event-btn';
                info.el.appendChild(addButton);

                addButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    selectedDate = info.date;
                    eventStartDateInput.value = selectedDate.toISOString().split('T')[0];
                    eventEndDateInput.value = selectedDate.toISOString().split('T')[0];
                    eventModal.show();
                });
            }
        });

        calendar.render();
    } else {
        console.error("Element #calendar nie został znaleziony.");
    }

    eventForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        var eventTitle = eventTitleInput.value;
        var eventStartDate = eventStartDateInput.value;
        var eventEndDate = eventEndDateInput.value;
        var eventStartTime = allDayEventCheckbox.checked ? '' : eventStartTimeInput.value;
        var eventEndTime = allDayEventCheckbox.checked ? '' : eventEndTimeInput.value;
        var eventColor = eventColorInput.value;

        var eventData = {
            title: eventTitle,
            start: eventStartDate + (eventStartTime ? 'T' + eventStartTime : ''),
            end: eventEndDate + (eventEndTime ? 'T' + eventEndTime : ''),
            color: eventColor,
            allDay: allDayEventCheckbox.checked
        };

        fetch('save_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.addEvent(eventData);
            } else {
                console.error('Error saving event:', data.error);
            }
            eventModal.hide();
            eventForm.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            eventModal.hide();
            eventForm.reset();
        });
    });
});
