<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disactivated Volunteers</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <section id="doctors" class="user-section">
            <h2>Disactivated Volunteers</h2>
            <div class="user-grid">
                @foreach($disactivated_volunteers as $disactivated_volunteer)
                <div class="user-card">
                    <img src="{{ $disactivated_volunteer->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $disactivated_volunteer->image) }}" alt="Doctor">
                    <h3>{{ucfirst($disactivated_volunteer->name)}}</h3>
                    <p>Email: {{ $disactivated_volunteer->email }}</p>
                    <p>Date of Birth: {{ $disactivated_volunteer->date_of_birth }}</p>
                    <p>Phone Number: {{$disactivated_volunteer->phone_number}}</p>
                    <form action="{{ url('/disactivated_volunteers') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $disactivated_volunteer->user_id }}">
                        <button type="submit" class="view-profile">Activate</button>
                    </form>
                    
                </div>
                @endforeach
            </div>
        </section>
    </main>
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>