<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Schedule</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        
        <div class="form-container">
            <a href="#top" class="scroll-button">^</a>
            <h2>Create a New Schedule for Dr.{{$doctor->name}}</h2>
            <form action="{{ url('/create_doctor_schedule/' . $doctor->doctor_id) }}" method="POST"  class="form">
                @csrf
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                @error('date')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="from">From:</label>
                    <input type="time" id="from" name="from_duration" placeholder="Eg:From 8:00 A.M. " required>
                </div>
                @error('from_duration')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="to">To:</label>
                    <input type="time" id="to" name="to_duration" placeholder="Eg:To 11:00 A.M. " required>
                </div>
                @error('to_duration')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="appointment_number">Appointments number</label>
                    <input type="number" id="appointment_number" name="appointment_number" required>
                </div>
                @error('appointment_number')
                {{$message}}
                @enderror
                <button type="submit" class="submit-btn">Create Schedule</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>
