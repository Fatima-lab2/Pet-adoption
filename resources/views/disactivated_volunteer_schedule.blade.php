<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Schedules</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <main class="main-container">
        <a href="#top" class="scroll-button"></a>
        <div class="container">
            <h2>{{ucfirst($user->name)}} Schedules & Tasks</h2>

            @foreach ($schedules as $schedule)

                @php
                    $scheduleTasks = collect($tasks)->where('volunteer_schedule_id', $schedule->volunteer_schedule_id);
                @endphp

                <div class="schedule-section">
                   
                    <p><strong>Date:</strong> {{ $schedule->date }}</p>
                    <p><strong>From:</strong> {{ $schedule->from_duration }}</p>
                    <p><strong>To:</strong> {{ $schedule->to_duration }}</p>

                    <table class="appointment-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($scheduleTasks as $task)
                                <tr>
                                    <td>{{ $task->start_time }} - {{ $task->end_time }}</td>
                                    <td>{{ $task->task_details }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center;">No tasks for this schedule.</td>
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
