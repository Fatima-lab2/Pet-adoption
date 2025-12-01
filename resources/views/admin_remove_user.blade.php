<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove User</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')
    <!-- Main Content Container -->
    <main class="main-container">
        <div class="remove-container">
            <h2>Remove User</h2>
            <p style="font-weight: 800">Are you sure you want to disactivate this user?</p>
            
            <!-- Form Container -->
            <div class="form-container">
                <form action="{{ url('/admin_remove_user/' . $user->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}"> <!-- Placeholder user ID -->
                    <!-- Button Container -->
                    <div class="button-container">
                    <button type="submit" class="remove-btn">Yes, Disactivate</button>
                    <a href="{{ url('/users' ) }}" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>

