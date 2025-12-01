<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}"> 
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .profile-header { display: flex; align-items: center; margin-bottom: 20px; }
        .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-right: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; font-weight: bold; }
        input, select, textarea { padding: 8px; width: 300px; }
        button, .btn { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0056b3; }
        .success { color: green; margin: 15px 0; }
        .error { color: red; margin: 15px 0; }
        .role-section { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    
    
    <div class="profile-header">
        <img src="{{ asset($user->image ?? 'images/default.png') }}" class="profile-img">
        <h1>Edit Profile <small>({{ $role_name }})</small></h1>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('profile_update') }}" enctype="multipart/form-data">
        @csrf
        <h2>Basic Information</h2>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
        </div>
        <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
        </div>
        <div class="form-group">
            <label>Profile Image:</label>
            <input type="file" name="image">
        </div>

        @if ($role_id == 2) <!-- Doctor -->
        <div class="role-section">
            <h2>Doctor Information</h2>
            <div class="form-group">
                <label>Specialization:</label>
                <input type="text" name="specialization" value="{{ old('specialization', $role_data->specialization ?? '') }}">
            </div>
            <div class="form-group">
                <label>Experience (Years):</label>
                <input type="number" name="experience_year" value="{{ old('experience_year', $role_data->experience_year ?? 0) }}">
            </div>
        </div>
        
        @elseif ($role_id == 3) <!-- Volunteer -->
        <div class="role-section">
            <h2>Volunteer Information</h2>
            <div class="form-group">
                <label>Responsibility:</label>
                <input type="text" name="responsibility" value="{{ old('responsibility', $role_data->responsibility ?? '') }}">
            </div>
        </div>
        
        @elseif ($role_id == 6) <!-- Employee -->
        <div class="role-section">
            <h2>Employee Information</h2>
            <div class="form-group">
                <label>Responsibility:</label>
                <input type="text" name="responsibility" value="{{ old('responsibility', $role_data->responsibility ?? '') }}">
            </div>
            <div class="form-group">
                <label>Type of Work:</label>
                <input type="text" name="type_of_work" value="{{ old('type_of_work', $role_data->type_of_work ?? '') }}">
            </div>
        </div>
        @endif

        <button type="submit">Update Profile</button>
        <a href="{{ route('profile_view') }}" class="btn">Cancel</a>
    </form>
</body>
</html>