<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Role</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
</head>
<body>
    @include('partials.nav-bar')

    <main class="edit-role-main">
        <div class="edit-role-container">
            <h2 style="text-align: center">Edit Role Description</h2>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="form-container">
            <form action="{{ url('/edit_role_description/' . $role->role_id) }}" method="POST"  class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Role Name:</label>
                    <input type="text" value="{{ ucfirst($role->role_name) }}" disabled>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="4" required>{{ $role->description }}</textarea>
                </div>
            
                <button type="submit" class="add-role-btn">Update Description</button>
            </form>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
