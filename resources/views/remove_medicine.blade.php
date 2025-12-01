<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Medicine</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <!-- Main Content Container -->
    <main class="main-container">
        <div class="remove-container">
            <h2>Remove Medicine</h2>
            <p>Are you sure you want to remove {{$medicine->name}}?</p>
            
            <!-- Form Container -->
            <div class="form-container">
                <form action="{{url('/remove_medicine/'.$medicine->medicine_id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="medicine_id" value="{{ $medicine->medicine_id}}">
                    <div class="button-container">
                        <button type="submit" class="remove-btn">Yes, Eliminate</button>
                        <a href="{{url('/medicines')}}" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>

