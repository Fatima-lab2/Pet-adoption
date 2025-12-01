<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Volunteer</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
     <link rel="icon" href="{{ asset('images/logo.png') }}">
</head>
<body>
    @include('partials.nav-bar')

    <div class="form-container">
        <h2>Add New Volunteer</h2>
        <form action="{{ url('/add_new_volunteer') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>
            @error('name') <p class="error">{{ $message }}</p> @enderror

            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" required>
            @error('phone_number') <p class="error">{{ $message }}</p> @enderror

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" id="date_of_birth" required>
            @error('date_of_birth') <p class="error">{{ $message }}</p> @enderror

            <button type="submit">Add Volunteer</button>
        </form>
    </div>
</body>
</html>
