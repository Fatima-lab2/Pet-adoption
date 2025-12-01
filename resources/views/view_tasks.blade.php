<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')
    @php use Carbon\Carbon; @endphp
    <main>
        <a href="#top" class="scroll-button">^</a>
        @if(session('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        </div>
        @endif
        <div class="appointment-management">

            <h3>Tasks for {{ucfirst($volunteer->name)}} Schedule on {{ $schedule->date }}</h3>

            <!-- Appointments Table -->
            <section class="appointment-list">
                <table>
                    <thead>
                        <tr>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Task Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{ Carbon::createFromFormat('H:i:s', $task->start_time)->format('H:i') }}</td>
                                <td>{{ Carbon::createFromFormat('H:i:s', $task->end_time)->format('H:i') }}</td>
                                <td>{{ $task->task_details }}</td>
                                <td><a href="{{url('/update_task/' .$schedule->volunteer_schedule_id.'/' .  $task->task_id)}}">Update|</a>
                                    <a href="{{url('/remove_task/' .$schedule->volunteer_schedule_id.'/' .  $task->task_id)}}">Diactivate</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="center-container">
                    <a href="{{ url('/add_new_task/' . $volunteer->volunteer_id . '/' . $schedule->volunteer_schedule_id) }}" class="add-task-link">Add New Task</a>
                </div>
            </section>
    
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>
