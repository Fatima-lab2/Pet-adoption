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
    @if(session('success'))
    <div class="alert alert-success">
    {{ session('success') }}
    </div>
    @endif
    <a href="#top" class="scroll-button" style="text-decoration: none">up</a>
        <div class="user-tab">
            <a href="#doctors">Doctors</a>
            <a href="#donors">Donors</a>
            <a href="#adopters">Adopters</a>
            <a href="#employees">Employees</a>
            <a href="#volunteers">Volunteers</a>
            <a href="#users">Users</a>
            <a href="{{ url('/add_new_user') }}">Add new user</a>
            <a href="{{ url('/role_management') }}">Roles</a>
        </div>
        
        <!-- Doctors Section -->
        <section id="doctors" class="user-section">
            <h2>Doctors</h2>
            <div class="user-grid">
                @foreach($doctors as $doctor)
                <div class="user-card">
                    <img src="{{ $doctor->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $doctor->image) }}" alt="Doctor">
                    <h3>Dr.{{ucfirst($doctor->name)}}</h3>
                    <p>Email: {{ $doctor->email }}</p>
                    <p>Date of Birth: {{ $doctor->date_of_birth }}</p>
                    <p>Specialization:{{$doctor ->specialization}}</p>
                    <p>Experience:{{$doctor ->experience_year}} years</p>
                    <p>Phone Number: {{$doctor->phone_number}}</p>
                    <a href="{{ url('/view_doctor_schedule/' . $doctor->user_id) }}" class="view-profile">Schedule</a>
                    <a href="{{ url('/update_profile/' . $doctor->user_id . '/' .$doctor->role_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/admin_remove_user/' . $doctor->user_id) }}" class="view-profile">Disactivate</a>
                    <a href="{{ url('/doctor_contracts/' . $doctor->doctor_id) }}" class="view-profile contract-link" >Contract</a>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Donors Section -->
        <section id="donors" class="user-section">
            <h2>Donors</h2>
            <div class="user-grid">
                @foreach($donors as $donor)
                <div class="user-card">
                    <img src="{{$donor->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $donor->image) }}" alt="Donor">
                    <h3>Name:{{ucfirst($donor->name)}}</h3>
                    <p>Email:{{$donor->email}}</p>
                    <p>Date of birth: {{$donor->date_of_birth}}</p>
                    <p>Previous Donation Number:{{$donor->previous_donation_times}}</p>
                    <p>Phone Number:{{$donor->phone_number}} </p>
                    <a href="{{ url('/update_profile/' . $donor->user_id . '/' .$donor->role_id)  }}" class="view-profile">Update</a>
                    <a href="{{ url('/admin_remove_user/' . $donor->user_id) }}" class="view-profile">Disactivate</a>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Adopters Section -->
        <section id="adopters" class="user-section">
            <h2>Adopters</h2>
            <div class="user-grid">
            @foreach($adopters as $adopter)
            <div class="user-card">
                    <img src="{{$adopter->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $adopter->image) }}" alt="Adopter">
                    <h3>Name:{{ucfirst($adopter->name)}}</h3>
                    <p>Date of birth: {{$adopter->date_of_birth}}</p>
                    <p>Adoption times:{{$adopter->previous_adoption_times}}</p>
                    <p>Email: {{$adopter->email}}</p>
                    <p>Phone Number: {{$adopter->phone_number}}</p>
                    <a href="{{ url('/update_profile/' . $adopter->user_id . '/' .$adopter->role_id)  }}" class="view-profile">Update</a>
                    <a href="{{ url('/admin_remove_user/' . $adopter->user_id) }}" class="view-profile">Disactivate</a>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Employees Section -->
        <section id="employees" class="user-section">
            <h2>Employees</h2>
            <div class="user-grid">
                @foreach($employees as $employee)
                <div class="user-card">
                    <img src="{{$employee->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $employee->image) }}"alt="Employee">
                    <h3>Name:{{ucfirst($employee->name)}}</h3>
                    <p>Email:{{$employee->email}}</p>
                    <p>Date of birth:{{$employee->date_of_birth}}</p>
                    <p>Responsibility:{{$employee->responsibility}}</p>
                    <p>Type of work:{{$employee->type_of_work}}</p>
                    <p>Phone number: {{$employee->phone_number}}</p>
                    <a href="{{ url('/update_profile/' . $employee->user_id . '/' .$employee->role_id)  }}" class="view-profile">Update</a>
                    <a href="{{ url('/admin_remove_user/' . $employee->user_id) }}" class="view-profile">Disactivate</a>
                    <a href="{{ url('/employee_contracts/' . $employee->employee_id) }}" class="view-profile contract-link" >Contract</a>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Volunteers Section -->
        <section id="volunteers" class="user-section">
            <h2>Volunteers</h2>
            <div class="user-grid">
                @foreach($volunteers as $volunteer)
                <div class="user-card">
                    <img src="{{$volunteer->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $volunteer->image) }}" alt="Volunteer">
                    <h3>Name:{{ucfirst($volunteer->name)}}</h3>
                    <p>Email:{{$volunteer->email}}</p>
                    <p>Date of birth:{{$volunteer->date_of_birth}}</p>
                    <p>Responsibility:{{$volunteer->responsibility}}</p>
                    <p>Phone number:{{$volunteer->phone_number}}</p>
                    <a href="{{ url('/update_profile/' . $volunteer->user_id . '/' .$volunteer->role_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/view_volunteer_schedule/' . $volunteer->user_id) }}" class="view-profile">Schedule</a>
                    <a href="{{ url('/admin_remove_user/' . $volunteer->user_id) }}" class="view-profile">Disactivate</a>
                </div>
                @endforeach
            </div>
        </section>
             <!-- Unassigned Section -->
             <section id="users" class="user-section">
            <h2>Unassigned Users</h2>
            <div class="user-grid">
                @foreach($unassignedUsers as $unassignedUser)
                <div class="user-card">
                    <img src="{{$unassignedUser->image === 'images/default.png' 
                    ? asset('images/default.png') 
                    : asset('storage/' . $unassignedUser->image) }}">
                    <h3>Name:{{ucfirst($unassignedUser->name)}}</h3>
                    <p>Email:{{$unassignedUser->email}}</p>
                    <p>Date of Birth:{{$unassignedUser->date_of_birth}}</p>
                    <p>Phone Number:{{$unassignedUser->phone_number}}</p>
                    <a href="{{ url('/assign_role/' . $unassignedUser->user_id) }}" class="view-profile">Assign Role</a>
                    <a href="{{ url('/admin_remove_user/' . $unassignedUser->user_id) }}" class="view-profile">Disactivate</a>
                </div>
                @endforeach
            </div>
            </section>
              <!-- Section added by admin-->
              @foreach($admin_roles as $admin_role)
            <section id="others" class="user-section">
                <h2>{{ucfirst($admin_role->role_name)}}</h2>
                <div class="user-grid">
                    <div class="user-card">
                        <img src="{{$admin_role->image === 'images/default.png' 
                        ? asset('images/default.png') 
                        : asset('storage/' . $admin_role->image) }}">
                        <h3>Name:{{ucfirst($admin_role->name)}}</h3>
                        <p>Email:{{$admin_role->email}}</p>
                        <p>Date of Birth:{{$admin_role->date_of_birth}}</p>
                        <p>Phone Number:{{$admin_role->phone_number}}</p>
                        <a href="{{ url('/update_profile/' . $admin_role->user_id . '/' .$admin_role->role_id) }}" class="view-profile">Update</a>
                        <a href="{{ url('/admin_remove_user/' . $admin_role->user_id) }}" class="view-profile">Disactivate</a>
                    </div>
                   
                </div>
                 @endforeach
            </section>
            <div class="center-link-wrapper">
                <a href="{{ url('/disactivated_users/') }}" class="view-profile disactivated-link">
                    Do you want to see disactivated users?
                </a>
            </div>
        </section>
    </main>
    
<!-- Footer -->
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>

