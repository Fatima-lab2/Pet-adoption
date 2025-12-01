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
            <h2>Remove Role</h2>
            <p>Are you sure you want to remove {{$role->role_name}} role?</p>
            
            <!-- Form Container -->
            <div class="form-container">
                <form action="{{ url('/remove_role/' . $role->role_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role_id" value="{{$role->role_id}}"> 
                    <!-- Button Container -->
                    <div class="button-container">
                        <button type="submit" class="remove-btn">Yes, Remove</button>
                        <a href="{{url('/users')}}" class="cancel-btn">Cancel</a>
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
