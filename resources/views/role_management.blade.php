<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
        @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

        <a href="#top" class="scroll-button">up</a>
        <div class="roles-container">
            <h2>Manage Roles</h2>

            <!-- Role List -->
            <table class="roles-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Number of Active Users</th>
                        <th>Number of Unactive Users</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td style="text-align: left">{{ucfirst($role->role_name)}}</td>
                        <td>{{$total_nb_roles[$role->role_id] ?? 0}}</td>
                        <td>{{$total_nb_roles2[$role->role_id] ?? 0}}</td>
                        <td style="text-align: left">{{$role->description}}</td>
                        <td><a  style="display: inline;padding: 10px ;color: #007bff;text-decoration: none;border-radius: 5px;font-weight: bold;text-align: center;"href="{{ url('/remove_role/' . $role->role_id)}} ">Diactivate 
                        <a style="display: inline;padding: 10px ;color: #007bff;text-decoration: none;border-radius: 5px;font-weight: bold;text-align: center;" href="{{ url('/edit_role_description/' . $role->role_id) }}">Edit </a>
                        </a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Add New Role -->
            <div class="add-role-container">
                <h2>Add New Role</h2>
                <form action="{{url('/role_management')}}" method="POST">
                    @csrf
                    <input type="text" name="role_name" placeholder="Enter Role Name" required>
                    <input type="text" name="description" placeholder="Enter Role Description" required>
                    <input type="text" name="details" placeholder="Enter other details" >
                    <button type="submit" class="add-role-btn" style="display:block;margin: 10px auto;">Add Role</button>
                </form>
                <a href="{{ url('/disactivated_roles') }}" class="btn-link" style="display: inline-block;padding: 10px 20px;background-color: #007bff;color: white;text-decoration: none;border-radius: 5px;font-weight: bold;text-align: center;">View Disactivated Roles</a>
            </div>
        </div>
    </main>
    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>
