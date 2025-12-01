<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Schedules</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <main class="main-container">
        <a href="#top" class="scroll-button">up</a>
        <div class="container">
            <h2>Doctor {{ucfirst($doctor->name)}} Schedules & Appointments</h2>

            @foreach ($schedules as $schedule)

                @php
                    $scheduleAppointments = collect($appointments)->where('doctor_schedule_id', $schedule->doctor_schedule_id);
                @endphp

                <div class="schedule-section">
                   
                    <p><strong>Date:</strong> {{ $schedule->date }}</p>
                    <p><strong>From:</strong> {{ $schedule->from_duration }}</p>
                    <p><strong>To:</strong> {{ $schedule->to_duration }}</p>

                    <table class="appointment-table">
                        <thead>
                            <tr>
                                <th>Animal Name</th>
                                <th>Time</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($scheduleAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->animal_name  }}</td>
                                    <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                    <td>{{ $appointment->appointment_details }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center;">No appointments for this schedule.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr style="margin: 30px 0;">
                </div>
            @endforeach
        </div>
    </main>

    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
