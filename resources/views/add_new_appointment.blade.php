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
    <h2 style="text-align: center">Add New Task for {{ ucfirst($doctor->name) }} - {{ $doctor->date }}</h2>

    <div class="form-container">
        <form class="auth-form" action="{{ url('/add_new_appointment/' . $doctor->doctor_id . '/' . $doctor->doctor_schedule_id) }}" method="POST">
            @csrf
            <label>Animal:
                <select name="animal_id" required>
                    <option value="">-- Select Animal --</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->animal_id }}">{{ $animal->animal_name }}</option>
                            @endforeach
                        </select>
                    </label>
            <label for="appointment_details">Details:</label>
            <textarea name="appointment_details" rows="4" required></textarea>
            @error('appointment_details')
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
