<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Appointment</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')
<main class="main-container">
    <a href="#top" class="scroll-button">^</a>
    <div class="form-container">
        <form class="auth-form" action="{{ url('/update_appointments/' .$doctor_schedule_id. '/'. $appointment->appointment_id) }}" method="POST">
            @csrf
            <h2 style="text-align:center;">Update Appointment</h2>
            <label for="animal_id">Animal:</label>
            <select name="animal_id" required>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->animal_id }}" {{ $appointment->animal_id == $animal->animal_id ? 'selected' : '' }}>
                        {{ $animal->animal_name }}
                    </option>
                @endforeach
            </select>
            @error('animal_id') <p class="error">{{ $message }}</p> @enderror

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" value="{{ $appointment->start_time }}" required>
            @error('start_time') <p class="error">{{ $message }}</p> @enderror

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" value="{{ $appointment->end_time }}" required>
            @error('end_time') <p class="error">{{ $message }}</p> @enderror

            <label for="appointment_details">Details:</label>
            <textarea name="appointment_details" rows="4" required>{{ $appointment->appointment_details }}</textarea>
            @error('appointment_details') <p class="error">{{ $message }}</p> @enderror

            <button type="submit" class="btn-submit">Update Appointment</button>
        </form>
    </div>
</main>
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
