@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Rentals Calendar</h1>
        </div>
    </div>
    <br>
    <div id="calendar"></div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="custom-modal-close">&times;</span>
        <h5 id="customModalTitle" class="text-center"><strong>Rental Details</strong></h5>
        <span><strong>Title:</strong> <span id="modalTitle"></span></span><br>
        <span><strong>Start Date:</strong> <span id="modalStart"></span></span><br>
        <span><strong>Return Date:</strong> <span id="modalEnd"></span></span>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    window.reservations = @json($reservations->map(function($reservation) {
        return [
            'title' => "{$reservation->car->brand} {$reservation->car->model} - {$reservation->user->name} {$reservation->user->surname}",
            'start' => $reservation->start_date,
            'end' => $reservation->end_date
        ];
    }));

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var customModal = document.getElementById('customModal');
        var modalClose = document.querySelector('.custom-modal-close');

        if (!calendarEl) {
            console.error('Calendar element not found.');
            return;
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [
                FullCalendar.plugins.dayGrid,
                FullCalendar.plugins.timeGrid,
                FullCalendar.plugins.list,
                FullCalendar.plugins.interaction
            ],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            initialView: 'dayGridMonth',
            events: window.reservations || [],
            eventClick: function(info) {
                document.getElementById('modalTitle').innerText = info.event.title;
                document.getElementById('modalStart').innerText = info.event.start.toLocaleString();
                document.getElementById('modalEnd').innerText = info.event.end ? info.event.end.toLocaleString() : 'N/A';

                customModal.style.display = 'block';
            }
        });

        calendar.render();

        modalClose.addEventListener('click', function() {
            customModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === customModal) {
                customModal.style.display = 'none';
            }
        });
    });
</script>

<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 45%;
        border-radius: 8px;
    }

    .custom-modal-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .custom-modal-close:hover,
    .custom-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
@endsection
