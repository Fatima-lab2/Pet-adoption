<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <a href="#top" class="scroll-button">^</a>
        @if(session('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        </div>
        @endif
        <div class="schedule-management">
            <!-- Doctor's Schedule Table -->
            <section class="schedule-list">
                @foreach($doctors as $doctor)
                <h2>Schedule for Dr. {{ ucfirst($doctor->name) }}</h2>
                <table style="width: 70%; margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Appointments Number</th>
                            <th>View Appointments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            @if ($schedule->doctor_id == $doctor->doctor_id)
                                <tr>
                                    <td>{{ $schedule->date }}</td>
                                  <td>{{ \Carbon\Carbon::parse($schedule->from_duration)->format('H:i') }} -> {{ \Carbon\Carbon::parse($schedule->to_duration)->format('H:i') }}</td>
                                    <td>{{ $schedule->appointment_number }}</td>
                                    <td>
                                        <a href="{{ url('/view_appointments/' . $doctor->doctor_id . '/' . $schedule->doctor_schedule_id) }}" class="view-appointments-btn" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">View Appointments</a>
                                    </td>
                                    <td>
                                        <a href="{{ url('/update_doctor_schedule/' . $doctor->doctor_id . '/' . $schedule->doctor_schedule_id) }}">Update|</a>
                                        <a href="{{ url('/remove_doctor_schedule/' . $doctor->doctor_id . '/' . $schedule->doctor_schedule_id) }}">DisActivate</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endforeach
            </section>
        </div>
    </main>

   
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>
