<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
    <a href="#top" class="scroll-button">^</a>
        <div class="doctor-management">
            <!-- Doctor List Section -->
            <section class="doctor-list">
                <h2 style="text-align: center">Doctors</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Specialty</th>
                            <th>Experience</th>
                            <th>View Schedules</th>
                            <th>Create Schedule & Appointments</th>
                            <th>View Contracts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        <tr>
                            <td>Dr. {{ucfirst($doctor->name)}}</td>
                            <td>{{$doctor->specialization}}</td>
                            <td>{{$doctor->experience_year}}</td>
                            <td><a href="{{url('/doctor_schedule/' . $doctor->doctor_id)}} " style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;"class="view-schedule-btn">View Schedule</a></td>
                            <td><a href="{{ url('/create_doctor_schedule/' . $doctor->doctor_id) }}" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;" class="create-schedule-btn">Create Schedule & Appointments</a></td>
                    
                            <td>
                            <a href="{{ url('/doctor_contracts/' . $doctor->doctor_id) }}" 
                           style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">
                            View Contracts
                            </a>
                        </td>
                        </tr>
                            @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </main>
    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
