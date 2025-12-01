<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Tasks</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('partials.nav-bar')

    <main class="container">
        <a href="#top" class="scroll-button">^</a>
        <h2>Create Tasks for  {{ ucfirst($volunteer->name) }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('/create_task/' . $volunteer_schedule->volunteer_schedule_id) }}" method="POST">
            @csrf
            <p>Tasks to create: <strong>{{ $volunteer_schedule->task_number }}</strong></p>

            @for($i = 0; $i < $volunteer_schedule->task_number; $i++)
                <fieldset style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px;">
                    <legend>Task {{ $i + 1 }}</legend>

                    <label>Start Time:
                        <input type="time" name="task[{{ $i }}][start_time]" required>
                    </label><br><br>

                    <label>End Time:
                        <input type="time" name="task[{{ $i }}][end_time]" required>
                    </label><br><br>
                    <label>Details:
                        <textarea name="task[{{ $i }}][task_details]" required></textarea>
                    </label>
                </fieldset>
            @endfor
            <button type="submit">Save Task</button>
        </form>
    </main>
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
