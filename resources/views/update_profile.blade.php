<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <div class="update-container">
            <h2>Update Profile</h2>

            <form action="{{ url('/update_profile/' . $user->user_id . '/' . $user->role_id) }}" method="POST" >
                @csrf
                <label for="image">Profile Image:</label>
                <img src="{{ $user->image === 'images/default.png' 
                ? asset('images/default.png') 
                : asset('storage/' . $user->image) }} ">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="{{$user->name}}" placeholder="Update full name" required>
                @error('name')
                {{$message}}
                @enderror
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="{{$user->email}}" placeholder="Update email" required>
                @error('email')
                {{$message}}
                @enderror
                <label for="date_of_birth">Date of Birth:</label>
                <input type="text" id="date_of_birth" name="date_of_birth"value="{{$user->date_of_birth}}"  placeholder="Update the birthdate" required>
                @error('date_of_birth')
                {{$message}}
                @enderror
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="{{$user->phone_number}}" placeholder="Update the birthdate" required>
                @error('phone_number')
                {{$message}}
                @enderror
                
               <!--  <label for="role">Role:</label> -->

                <!-- <select id="role_id" name="role_id" class="form-select">-->
                <!--   <option value="">Select Role</option> -->
                <!--   @foreach ($roles as $role) -->
                <!--  <option value="{{ $role->role_id }}" {{ $user->role_id == $role->role_id ? 'selected' : '' }}>-->
                <!--    {{ $role->role_id }} - {{ ucfirst($role->role_name) }}-->
                <!--    </option> -->
                 <!--   @endforeach -->
                <!--</select>-->
            
               
                @if($user->role_id == 2 && isset($doctor))
                <label for="experience_year">Experience Years:</label>
                <input type="text" id="experience_year" name="experience_year" value="{{$doctor->experience_year}}" placeholder="Update experience year" required>
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" value="{{$doctor->specialization}}" placeholder="Update specialization" required>
                
                @endif

                @if($user->role_id == 3 && isset($volunteer))
                <label for="responsibility">Responsibility:</label>
                <input type="text" id="responsibility" name="responsibility" value="{{$volunteer->responsibility}}" placeholder="Update " required>
               
                @endif
                @if($user->role_id == 4 && isset($adopter))
                <label for="previous_adoption_times">Previous adoption number:</label>
                <input type="text" id="previous_adoption_times" name="previous_adoption_times" value="{{$adopter->previous_adoption_times}}" placeholder="Update experience year" required>
                @endif

                @if($user->role_id == 5 && isset($donor))
                <label for="previous_donation_times">Previous donation times:</label>
                <input type="text" id="previous_donation_times" name="previous_donation_times" value="{{$donor->previous_donation_times}}" placeholder="Update experience year" required>
                @endif
                @if($user->role_id == 6 && isset($employee))
                <label for="responsibility">Responsibility:</label>
                <input type="text" id="responsibility" name="responsibility" value="{{$employee->responsibility}}" placeholder="Update experience year" required>
                @error('responsibility')
                {{$message}}
                @enderror
                <label for="type_of_work">Type of work:</label>
                <input type="text" id="type_of_work" name="type_of_work" value="{{$employee->type_of_work}}" placeholder="Update experience year" required>
                @error('type_of_work')
                {{$message}}
                @enderror
                @endif
                
          <button type="submit">Update Profile</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
