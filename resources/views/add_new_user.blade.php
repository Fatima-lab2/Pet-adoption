<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <main>
        <a href="#top" class="scroll-button">^</a>
        <div class="form-container">
            <h2>Add New User</h2>
            <form action="{{ url('/add_new_user') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="full-name" name="name" required>
                </div>
                @error('name')
                {{$message}}
                @enderror

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                @error('email')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                @error('password')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="dob" name="date_of_birth" required>
                </div>
                @error('date_of_birth')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="phone_number">Phone Number: </label>
                    <input type="number" id="dob" name="phone_number" required>
                </div>
                @error('phone_number')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->role_id }}">{{ ucfirst($role->role_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="image" value="images/default.png">
                @error('role_id')
                {{$message}}
                @enderror
                <button type="submit" class="submit-btn">Add User</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>

