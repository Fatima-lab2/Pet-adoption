<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disactivated Roles</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')

    <main>
        <a href="#top" class="scroll-button">Up</a>
        <div class="roles-container">
            <h2>Disactivated Roles</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(count($roles) > 0)
                <table class="roles-table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ ucfirst($role->role_name) }}</td>
                            <td style="text-align: left">{{ $role->description }}</td>
                            <td>
                                <form action="{{ url('/disactivated_roles/' . $role->role_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-link">Reactivate</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No disactivated roles found.</p>
            @endif
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
