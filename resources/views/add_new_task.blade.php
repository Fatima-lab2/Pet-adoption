<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Task</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')

<main class="schedule-management">
    <a href="#top" class="scroll-button">^</a>
    <h2 style="text-align: center">Add New Task for {{ ucfirst($volunteer->name) }} - {{ $volunteer->date }}</h2>

    <div class="form-container">
        <form class="auth-form" action="{{ url('/add_new_task/' . $volunteer->volunteer_id . '/' . $volunteer->volunteer_schedule_id) }}" method="POST">
            @csrf

            <label for="task_details">Details:</label>
            <textarea name="task_details" rows="4" required></textarea>
            @error('task_details')
            <div class="error">{{ $message }}</div>
            @enderror

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required>
            @error('start_time')
            <div class="error">{{ $message }}</div>
            @enderror

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required>
            @error('end_time')
            <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-submit">Add Task</button>
        </form>
    </div>
</main>

<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
