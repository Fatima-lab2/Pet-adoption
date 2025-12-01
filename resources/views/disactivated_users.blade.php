<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <section id="doctors" class="user-section">
            <h2>Disactivated Users</h2>
            <div class="user-grid">
                @foreach($disactivated_users as $disactivated_user)
                <div class="user-card">
                    <img src="{{ $disactivated_user->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $disactivated_user->image) }}" alt="Doctor">
                    <h3>{{ucfirst($disactivated_user->name)}}</h3>
                    <p>Email: {{ $disactivated_user->email }}</p>
                    <p>Date of Birth: {{ $disactivated_user->date_of_birth }}</p>
                    <p>Phone Number: {{$disactivated_user->phone_number}}</p>
                    <p>Phone Number: {{$disactivated_user->user_id}}</p>

                    @if($disactivated_user->role_id === 2)
                    @foreach($doctors as $doctor)
                     @if($doctor->user_id == $disactivated_user->user_id)
                    <p>Experience Years: {{ $doctor->experience_year }}</p>
                    <p>Specialization: {{ $doctor->specialization }}</p>

                    <!-- Link to doctor's schedule and appointments -->
                    <a href="{{ url('/disactivated_doctor_schedule/' . $doctor->doctor_id) }}" class="view-profile" style="margin-top: 10px; display: inline-block;">View Schedule & Appointments</a>
                    @endif
                @endforeach
                 @endif

                    {{-- Volunteer Details --}}
                    @if($disactivated_user->role_id === 3)
                        @foreach($volunteers as $volunteer)
                            @if($volunteer->user_id == $disactivated_user->user_id)
                                <p>Responsibility: {{ $volunteer->responsibility }}</p>
                                <a href="{{ url('/disactivated_volunteer_schedule/' . $volunteer->volunteer_id) }}" class="view-profile" style="margin-top: 10px; display: inline-block;">View Schedule & Appointments</a>
                            @endif
                        @endforeach
                    @endif

                    {{-- Adopter Details --}}
                    @if($disactivated_user->role_id == 4)
                        @foreach($adopters as $adopter)
                            @if($adopter->user_id == $disactivated_user->user_id)
                                <p>Previous Adoption Times: {{ $adopter->previous_adoption_times }}</p>
                            @endif
                        @endforeach
                    @endif

                    {{-- Donor Details --}}
                    @if($disactivated_user->role_id == 5)
                        @foreach($donors as $donor)
                            @if($donor->user_id == $disactivated_user->user_id)
                                <p>Previous Donation Times: {{ $donor->previous_donation_times }}</p>
                            @endif
                        @endforeach
                    @endif

    {{-- Employee Details --}}
    @if($disactivated_user->role_id == 6)
        @foreach($employees as $employee)
            @if($employee->user_id == $disactivated_user->user_id)
                <p>Responsibility: {{ $employee->responsibility }}</p>
                <p>Type of Work: {{ $employee->type_of_work }}</p>
            @endif
        @endforeach
    @endif
                    <form action="{{ url('/disactivated_users') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $disactivated_user->user_id }}">
                        <label for="is_active">Status:</label>
                        <select name="is_active">
                            <option value="0" selected>Inactive</option>
                            <option value="1">Active</option>
                        </select>

                        <button type="submit" class="view-profile">Activate</button>
                    </form>
                </div>
                @endforeach
            </div>
        </section>
    </main>
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>