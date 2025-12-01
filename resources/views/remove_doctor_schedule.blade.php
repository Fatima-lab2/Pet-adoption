<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Doctor Schedule</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <!-- Main Content Container -->
    <main class="main-container">
        <div class="remove-container">
            <h2>Remove Schedule</h2>
            <p>Are you sure you want to remove the schedule of Dr.{{ucfirst($doctor->name)}} ?</p>
            
            <!-- Form Container -->
            <div class="form-container">
                <form action="{{url('/remove_doctor_schedule/'.$doctor->doctor_id . '/' .$schedule->doctor_schedule_id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="doctor_schedule_id" value="{{ $schedule->doctor_schedule_id }}">
                    <div class="button-container">
                        <button type="submit" class="remove-btn">Yes, Remove</button>
                        <a href="{{url('/doctor_schedule/' . $doctor->doctor_id) }}" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
