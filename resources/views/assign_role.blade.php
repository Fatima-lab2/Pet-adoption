<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assign Role</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
 <!-- Navbar -->
 @include('partials.nav-bar')
<body>
    <div class="container">
    <div class="form-card">
        <h2>Assign Role to {{ $user->name }}</h2>
        <form action="{{ url('/assign_role/' . $user->user_id ) }}" method="POST" class="auth-form">
            @csrf
            @method('PUT')
            <select name="role_id" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->role_id }}">{{ ucfirst($role->role_name) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn">Assign Role</button>
        </form>
    </div>
</div>
<!-- Footer -->
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>