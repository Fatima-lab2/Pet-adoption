<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
@include('partials.nav-bar')
<body>
    
    <div class="container">
        <div class="form-card">
            <h2>Signup</h2>
            <form method="POST"  action="/register"  enctype="multipart/form-data" class="auth-form" >
                @csrf
                <input type="text" placeholder="Full Name" name="name" value="{{old('name')}}" required>
                @error('name')
                {{$message}}
                @enderror
                <input type="email" placeholder="Email" name="email" value="{{old('email')}}" required>
                @error('email')
                {{$message}}
                @enderror
                <input type="password" placeholder="Password" name="password"  required>
                @error('password')
                {{$message}}
                @enderror
                <input type="number" placeholder="Phone Number" name="phone_number"  value="{{old('phone_number')}}" required>
                @error('phone_number')
                {{$message}}
                @enderror
                <input type="date" placeholder="Date of Birth" name="date_of_birth" value="{{old('date_of_birth')}}" required>
                @error('date_of_birth')
                {{$message}}
                @enderror
                <input type="file" accept="images/*" name="image">
                {{--<select name="role_id" class="form-select">
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}">{{ $role->role_id }} - {{ ucfirst($role->role_name) }}</option>
                    @endforeach
                </select>
                
                
                @error('role_id')
                {{$message}}
                @enderror--}}
                <button type="submit" class="btn">Sign Up</button>
                <p class="login-link">Already have an account? <a href="/login">Login</a></p>
            </form>
        </div>
    </div>
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>

