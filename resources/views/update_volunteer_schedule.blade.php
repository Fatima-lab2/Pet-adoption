<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Volunteer Schedule</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')

<main class="schedule-management">
    <a href="#top" class="scroll-button">^</a>
    <h2 style="text-align: center">Update Schedule for the volunteer {{ucfirst($volunteer->name) }}</h2>
    <div class="form-container">
    <form class="auth-form" action="{{ url('/update_volunteer_schedule/' . $volunteer->volunteer_id . '/' . $schedule->volunteer_schedule_id) }}" method="POST">
        @csrf

        <label for="date">Date:</label>
        <input type="date" name="date" value="{{ $schedule->date }}" required>
        @error('date')
        {{$message}}
        @enderror
        <label for="from_duration">From Time:</label>
        <input type="time" name="from_duration" value="{{ old('from_duration', \Carbon\Carbon::parse($schedule->from_duration)->format('H:i')) }}">
        @error('from_duration')
        {{$message}}
        @enderror
        <label for="to_duration">To Time:</label>
        <input type="time" name="to_duration" value="{{ old('to_duration', \Carbon\Carbon::parse($schedule->to_duration)->format('H:i')) }}">
        @error('to_duration')
        {{$message}}
        @enderror
        <label for="task_number">Number of tasks:</label>
        <input type="number" name="task_number" value="{{ $schedule->task_number }}" required>
        @error('task_number')
        {{$message}}
        @enderror
        <button type="submit" class="btn-submit">Update Schedule</button>
    </form>
</div>
</main>

<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
