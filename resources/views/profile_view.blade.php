<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
   <link rel="icon" href="{{ asset('images/logo.png') }}">    
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .profile-header { display: flex; align-items: center; margin-bottom: 20px; }
        .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-right: 20px; }
        .profile-info { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; font-weight: bold; }
        .info-value { display: inline-block; }
        .action-buttons { margin-top: 20px; }
        button, .btn { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0056b3; }
        .role-section { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
   

    <div class="profile-header">
        <img src="{{ $user->image === 'images/default.png' 
        ? asset('images/default.png') 
        : asset('storage/' . $user->image) }}" alt="" class="profile-img">

        <h1>{{ htmlspecialchars($user->name) }} <small>({{ $role_name }})</small></h1>
    </div>

    @if (session('success'))
        <div style="color: green; margin: 15px 0;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="color: red; margin: 15px 0;">{{ session('error') }}</div>
    @endif

    <h2>Basic Information</h2>
    <div class="profile-info">
        <label>Full Name:</label>
        <span class="info-value">{{ htmlspecialchars($user->name) }}</span>
    </div>
    <div class="profile-info">
        <label>Email:</label>
        <span class="info-value">{{ htmlspecialchars($user->email) }}</span>
    </div>
    <div class="profile-info">
        <label>Phone Number:</label>
        <span class="info-value">{{ htmlspecialchars($user->phone_number ?? 'N/A') }}</span>
    </div>
    <div class="profile-info">
        <label>Date of Birth:</label>
        <span class="info-value">{{ htmlspecialchars($user->date_of_birth ?? 'N/A') }}</span>
    </div>

    @if ($role_id == 2) <!-- Doctor -->
    <div class="role-section">
        <h2>Doctor Information</h2>
        <div class="profile-info">
            <label>Specialization:</label>
            <span class="info-value">{{ htmlspecialchars($role_data->specialization ?? 'N/A') }}</span>
        </div>
        <div class="profile-info">
            <label>Experience (Years):</label>
            <span class="info-value">{{ htmlspecialchars($role_data->experience_year ?? '0') }}</span>
        </div>
    </div>
    
    @elseif ($role_id == 3) <!-- Volunteer -->
    <div class="role-section">
        <h2>Volunteer Information</h2>
        <div class="profile-info">
            <label>Responsibility:</label>
            <span class="info-value">{{ htmlspecialchars($role_data->responsibility ?? 'N/A') }}</span>
        </div>
    </div>
    
    @elseif ($role_id == 6) <!-- Employee -->
    <div class="role-section">
        <h2>Employee Information</h2>
        <div class="profile-info">
            <label>Responsibility:</label>
            <span class="info-value">{{ htmlspecialchars($role_data->responsibility ?? 'N/A') }}</span>
        </div>
        <div class="profile-info">
            <label>Type of Work:</label>
            <span class="info-value">{{ htmlspecialchars($role_data->type_of_work ?? 'N/A') }}</span>
        </div>
    </div>
    @endif

    <div class="action-buttons">
        <a href="{{ route('profile_edit') }}" class="btn">Edit Profile</a>
        <a href="{{ route('change_password') }}" class="btn">Change Password</a>
       <a href="/index" class="btn">Back to Home</a>
    </div>
</body>
</html>