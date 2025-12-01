<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Volunteer Schedule</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @php
    use Carbon\Carbon;
    @endphp
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
            <!-- Volunteer's Schedule Table -->
            <section class="schedule-list">
                @foreach($volunteers as $volunteer)
                <h2>Schedule for the volunteer {{ ucfirst($volunteer->name) }}</h2>
                <table style="width: 70%; margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Tasks Number</th>
                            <th>View Tasks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            @if ($schedule->volunteer_id == $volunteer->volunteer_id)
                                <tr>
                                    <td>{{ $schedule->date }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->from_duration)->format('H:i') }} -> {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->to_duration)->format('H:i') }}</td>
                                    <td>{{ $schedule->task_number }}</td>
                                    <td>
                                        <a href="{{ url('/view_tasks/' . $volunteer->volunteer_id . '/' . $schedule->volunteer_schedule_id) }}" class="view-appointments-btn" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">View Tasks</a>
                                    </td>
                                    <td>
                                        <a href="{{ url('/update_volunteer_schedule/' . $volunteer->volunteer_id . '/' . $schedule->volunteer_schedule_id) }}">Update|</a>
                                        <a href="{{ url('/remove_volunteer_schedule/' . $volunteer->volunteer_id . '/' . $schedule->volunteer_schedule_id) }}">DisActivate</a>
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
