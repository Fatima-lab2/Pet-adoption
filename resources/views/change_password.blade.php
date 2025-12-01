<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; font-weight: bold; }
        input { padding: 8px; width: 250px; }
        button, .btn { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0056b3; }
        .success { color: green; margin: 15px 0; }
        .error { color: red; margin: 15px 0; }
    </style>
</head>
<body>
    
    
    <h1>Change Password</h1>
    
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="post" action="{{ route('password_update') }}">
        @csrf
        <div class="form-group">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-group">
            <label>New Password:</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <button type="submit">Change Password</button>
        <a href="{{ route('profile_view') }}" class="btn">Cancel</a>
    </form>
</body>
</html>