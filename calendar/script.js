document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    var editEventModal = new bootstrap.Modal(document.getElementById('editEventModal'));
    var eventForm = document.getElementById('eventForm');
    var editEventForm = document.getElementById('editEventForm');
    var eventTitleInput = document.getElementById('eventTitle');
    var eventStartDateInput = document.getElementById('eventStartDate');
    var eventEndDateInput = document.getElementById('eventEndDate');
    var eventStartTimeInput = document.getElementById('eventStartTime');
    var eventEndTimeInput = document.getElementById('eventEndTime');
    var eventColorInput = document.getElementById('eventColor');
    var eventNoteInput = document.getElementById('eventNote');
    var allDayEventCheckbox = document.getElementById('allDayEvent');
    var timeFields = document.getElementById('timeFields');

    var editEventIdInput = document.getElementById('editEventId');
    var editEventTitleInput = document.getElementById('editEventTitle');
    var editEventStartDateInput = document.getElementById('editEventStartDate');
    var editEventEndDateInput = document.getElementById('editEventEndDate');
    var editEventStartTimeInput = document.getElementById('editEventStartTime');
    var editEventEndTimeInput = document.getElementById('editEventEndTime');
    var editEventColorInput = document.getElementById('editEventColor');
    var editEventNoteInput = document.getElementById('editEventNote');
    var editAllDayEventCheckbox = document.getElementById('editAllDayEvent');
    var editTimeFields = document.getElementById('editTimeFields');
    var deleteEventButton = document.getElementById('deleteEventButton');

    allDayEventCheckbox.addEventListener('change', function() {
        if (allDayEventCheckbox.checked) {
            timeFields.style.opacity = '0';
            setTimeout(() => timeFields.style.display = 'none', 300);
        } else {
            timeFields.style.display = 'flex';
            setTimeout(() => timeFields.style.opacity = '1', 0);
        }
    });

    editAllDayEventCheckbox.addEventListener('change', function() {
        if (editAllDayEventCheckbox.checked) {
            editTimeFields.style.opacity = '0';
            setTimeout(() => editTimeFields.style.display = 'none', 300);
        } else {
            editTimeFields.style.display = 'flex';
            setTimeout(() => editTimeFields.style.opacity = '1', 0);
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
                eventNoteInput.value = '';
                eventModal.show();
            },
            eventClick: function(info) {
                var event = info.event;
                console.log("Clicked event:", event);

                editEventIdInput.value = event.id;
                console.log("Setting event ID for editing:", event.id);
                editEventTitleInput.value = event.title;
                editEventStartDateInput.value = event.startStr.split('T')[0];
                editEventEndDateInput.value = event.endStr ? event.endStr.split('T')[0] : event.startStr.split('T')[0];
                editEventStartTimeInput.value = event.startStr.includes('T') ? event.startStr.split('T')[1].slice(0, 5) : '';
                editEventEndTimeInput.value = event.endStr && event.endStr.includes('T') ? event.endStr.split('T')[1].slice(0, 5) : '';
                editEventColorInput.value = event.backgroundColor;
                editEventNoteInput.value = event.extendedProps.note || '';
                editAllDayEventCheckbox.checked = event.allDay;

                editTimeFields.style.display = editAllDayEventCheckbox.checked ? 'none' : 'flex';
                editTimeFields.style.opacity = editAllDayEventCheckbox.checked ? '0' : '1';
                editEventModal.show();
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
                    eventNoteInput.value = '';
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
        var eventNote = eventNoteInput.value;

        var eventData = {
            title: eventTitle,
            start: eventStartDate + (eventStartTime ? 'T' + eventStartTime : ''),
            end: eventEndDate + (eventEndTime ? 'T' + eventEndTime : ''),
            color: eventColor,
            allDay: allDayEventCheckbox.checked,
            note: eventNote
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
                eventData.id = data.id; 
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

    editEventForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var eventId = editEventIdInput.value;
        var eventTitle = editEventTitleInput.value;
        var eventStartDate = editEventStartDateInput.value;
        var eventEndDate = editEventEndDateInput.value;
        var eventStartTime = editAllDayEventCheckbox.checked ? '' : editEventStartTimeInput.value;
        var eventEndTime = editAllDayEventCheckbox.checked ? '' : editEventEndTimeInput.value;
        var eventColor = editEventColorInput.value;
        var eventNote = editEventNoteInput.value;

        var eventData = {
            id: eventId,
            title: eventTitle,
            start: eventStartDate + (eventStartTime ? 'T' + eventStartTime : ''),
            end: eventEndDate + (eventEndTime ? 'T' + eventEndTime : ''),
            color: eventColor,
            allDay: editAllDayEventCheckbox.checked,
            note: eventNote
        };

        fetch('update_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var event = calendar.getEventById(eventId);
                if (event) {
                    event.remove();
                    calendar.addEvent(eventData);
                }
            } else {
                console.error('Error updating event:', data.error);
            }
            editEventModal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            editEventModal.hide();
        });
    });

    deleteEventButton.addEventListener('click', function() {
        var eventId = editEventIdInput.value;

        console.log("Attempting to delete event with ID:", eventId);

        fetch('delete_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: eventId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Event deleted successfully:", eventId);
                var event = calendar.getEventById(eventId);
                if (event) {
                    event.remove();
                }
            } else {
                console.error('Error deleting event:', data.error);
            }
            editEventModal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            editEventModal.hide();
        });
    });
});
