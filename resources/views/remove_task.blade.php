<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Task</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <!-- Main Content Container -->
    <main class="main-container">
        <div class="remove-container">
            <h2>Remove Task</h2>
            <p>Are you sure you want to remove the task ({{$task->task_details}}) ?</p>
            
            <!-- Form Container -->
            <div class="form-container">
                <form action="{{url('/remove_task/'.$schedule->volunteer_schedule_id.'/' .$task->task_id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->task_id}}">
                    <div class="button-container">
                        <button type="submit" class="remove-btn">Yes, Remove</button>
                        <a href="{{url('/volunteer_schedule/' . $volunteer->volunteer_id) }}" class="cancel-btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
