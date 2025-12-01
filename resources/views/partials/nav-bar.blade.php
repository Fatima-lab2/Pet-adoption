<header>
    <a href="{{ url('/index') }}">
    <img src="{{ asset('images/logo.png') }}" alt="Shelter Logo" class="logo">
    </a>
    <nav class="navbar" id="top">
        <ul class="nav-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/index') }}">Animals</a></li>
            
            @role('admin')
            <li><a href="{{ url('/users') }}">Users</a></li>
            <li><a href="{{ url('/donors') }}">Donations</a></li>
            <li><a href="{{ url('/adoptions') }}">Adoptions</a></li>
             <li><a href="{{ url('/medicines') }}">Medicines</a></li>
            @endrole
            @role('employee')
            @if(Auth::user()?->employee?->department_id == 2)
            <li><a href="{{ url('/doctors') }}">Doctors</a></li>
            <li><a href="{{ url('/volunteers') }}">Volunteers</a></li>
            @endif
           @if(Auth::user()?->employee?->department_id == 1)
            <li><a href="{{ url('/medicines') }}">Medicines</a></li>
            <li><a href="{{ url('/manage_food') }}">Manage food</a></li>
            <li><a href="{{ url('/expenses') }}">Expenses</a></li>
            @endif
            @if(Auth::user()?->employee?->department_id == 6)
            <li><a href="{{ url('/manage_animals') }}">Manage animal</a></li>
            <li><a href="{{ url('/room') }}">Room</a></li>
            @endif
           @endrole
           @auth
            <li><a href="{{ url('/make_donation') }}">Donate</a></li>
            @endauth
            @guest
            <li><a href="{{ url('/signup') }}">Signup</a></li>
            <li><a href="{{ url('/login') }}">Login</a></li>
            @endguest
        
            @role('doctor')
            <li><a href="{{ url('/doctor_dashboard') }}">Dashborad</a></li>
            <li><a href="{{ url('/view_medicines_vaccinations') }}">Medicines</a></li>
              <li><a href="{{ url('/view_schedule') }}">Schedule</a></li>
               <li><a href="{{ url('/search_animals') }}">Search</a></li>
           @endrole
            @role('volunteer')
            <li><a href="{{ url('/room') }}">Room</a></li>
            <li><a href="{{ url('/schedule_volunteer') }}">Schedule</a></li>
           @endrole
           @auth
            <li><a href="{{ url('/profile_view') }}">Profile</a></li>
            @endauth
             @auth
            <li><a href="{{ url('/logout') }}">Logout</a></li>
            @endauth
        </ul>
    </nav>
</header>

  