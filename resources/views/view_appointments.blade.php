<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
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
        <div class="appointment-management">

            <h3>Appointments for Schedule on {{ $schedule->date }}</h3>

            <!-- Appointments Table -->
            <section class="appointment-list">
                <table>
                    <thead>
                        <tr>
                            <th>Animal Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->animal_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td>{{ $appointment->appointment_details }}</td>
                                <td><a href="{{url('/update_appointments/' .$schedule->doctor_schedule_id.'/' .  $appointment->appointment_id)}}">Update|</a>
                                    <a href="{{url('/remove_appointments/' .$schedule->doctor_schedule_id.'/' .  $appointment->appointment_id)}}">Diactivate</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 <div class="center-container">
                    <a href="{{ url('/add_new_appointment/' . $schedule->doctor_id . '/' . $schedule->doctor_schedule_id) }}" class="add-task-link">Add New Appointment</a>
                </div>
            </section>
        
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>
