<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Appointments</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('partials.nav-bar')

    <main class="container">
        <a href="#top" class="scroll-button">^</a>
        <h2>Create Appointments for Dr. {{ ucfirst($doctor->name) }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('/create_appointments/' . $doctor_schedule->doctor_schedule_id) }}" method="POST">
            @csrf
            <p>Appointments to create: <strong>{{ $doctor_schedule->appointment_number }}</strong></p>

            @for($i = 0; $i < $doctor_schedule->appointment_number; $i++)
                <fieldset style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px;">
                    <legend>Appointment {{ $i + 1 }}</legend>

                    <label>Animal:
                        <select name="appointments[{{ $i }}][animal_id]" required>
                            <option value="">-- Select Animal --</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->animal_id }}">{{ $animal->animal_name }}</option>
                            @endforeach
                        </select>
                    </label><br><br>

                    <label>Start Time:
                        <input type="time" name="appointments[{{ $i }}][start_time]" required>
                    </label><br><br>

                    <label>End Time:
                        <input type="time" name="appointments[{{ $i }}][end_time]" required>
                    </label><br><br>
                    <label>Details:
                        <textarea name="appointments[{{ $i }}][appointment_details]" required></textarea>
                    </label>
                </fieldset>
            @endfor
            <button type="submit">Save Appointments</button>
        </form>
    </main>
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
