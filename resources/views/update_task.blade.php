<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Task</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')
<main class="main-container">
    <a href="#top" class="scroll-button">^</a>
    @php use Carbon\Carbon; @endphp
    <div class="form-container">
        <form class="auth-form" action="{{ url('/update_task/' .$volunteer_schedule_id. '/'. $task->task_id) }}" method="POST">
            @csrf
            <h2 style="text-align:center;">Update Task</h2>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" value="{{ Carbon::createFromFormat('H:i:s', $task->start_time)->format('H:i') }}" required>
            @error('start_time') <p class="error">{{ $message }}</p> @enderror

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" value="{{ Carbon::createFromFormat('H:i:s', $task->end_time)->format('H:i') }}" required>
            @error('end_time') <p class="error">{{ $message }}</p> @enderror
            <label for="task_details">Details:</label>
            <textarea name="task_details" rows="4" required>{{ $task->task_details }}</textarea>
            @error('task_details') <p class="error">{{ $message }}</p> @enderror

            <button type="submit" class="btn-submit">Update Task</button>
        </form>
    </div>
</main>
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
