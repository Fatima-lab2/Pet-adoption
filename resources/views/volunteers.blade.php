<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Volunteers</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        @if(isset($success))
        <div class="alert alert-success">{{ $success }}</div>
    @endif
     @if(isset($error))
        <div class="alert alert-success">{{ $error }}</div>
    @endif
    <a href="#top" class="scroll-button">^</a>
        <div class="doctor-management">
            <!-- Doctor List Section -->
            <section class="doctor-list">
                <h2 style="text-align: center">Volunteers</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Volunteer Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>View Schedules</th>
                            <th>Create Schedule </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($volunteers as $volunteer)
                        <tr>
                            <td>{{ucfirst($volunteer->name)}}</td>
                            <td>{{$volunteer->email}}</td>
                            <td>{{$volunteer->phone_number}}</td>
                            <td><a href="{{url('/volunteer_schedule/' . $volunteer->volunteer_id)}} " style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;"class="view-schedule-btn">View Schedule</a></td>
                            <td><a href="{{ url('/create_volunteer_schedule/' . $volunteer->volunteer_id) }}" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;" class="create-schedule-btn">Create New Schedule </a></td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <div class="center-link-wrapper">
                <a href="{{ url('/add_new_volunteer') }}" class="view-profile disactivated-link" style="background-color: #3d3dd0">Add New Volunteer</a>
            </div>

            <div class="center-link-wrapper">
                <a href="{{ url('/disactivated_volunteers') }}" class="view-profile disactivated-link">
                 Disactivated volunteers
                </a>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
