<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
@include('partials.nav-bar')
<body>

   <div class="container">
    @if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    <div class="login-wrapper">
        <!-- Left side with text -->
        <div class="login-left">
            <h2>Welcome Back!</h2>
       
        <blockquote class="inspire-quote">
                “Saving one animal won’t change the world, but it will change the world for that one animal.”
            </blockquote>        </div>

        <!-- Right side with form -->
        <div class="login-right">
            <h2>Login</h2>
            <form method="POST" action="/login" class="auth-form">
                @csrf
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                @error('email') 
                <p style="color:red">{{ $message }}</p>
                @enderror

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                @error('password') 
                <p style="color:red">{{ $message }}</p>
                @enderror

                <button type="submit">Login</button>
                <p class="login-link">Don't have an account? <a href="/signup">Sign Up</a></p>
            </form>
        </div>
    </div>
</div>

 <!-- Footer -->
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
