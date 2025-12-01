<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Volunteer Schedule</title>
       <link rel="icon" href="{{ asset('images/logo.png') }}">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Navigation bar */
nav {
    background-color: #f8f9fa;
    padding: 10px 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

/* Header styles */
h1 {
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 2rem;
}

h2 {
    color: #3498db;
    margin: 25px 0 15px;
    font-size: 1.5rem;
}

h3 {
    color: #495057;
    margin: 20px 0 15px;
    font-size: 1.3rem;
}

/* Schedule card */
.schedule-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.schedule-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.schedule-date {
    font-weight: 600;
    color: #2c3e50;
    margin-right: 15px;
}

.schedule-time {
    color: #6c757d;
}

.schedule-status {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
}

.schedule-status[data-status="Active"] {
    background-color: #d4edda;
    color: #155724;
}

.schedule-status[data-status="Inactive"] {
    background-color: #f8d7da;
    color: #721c24;
}

/* Task items */
.task-item {
    display: flex;
    margin-bottom: 10px;
    padding: 12px 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

.task-item:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.task-time {
    width: 180px;
    font-weight: 600;
    color: #2c3e50;
}

.task-details {
    flex-grow: 1;
    color: #495057;
}

/* List styles */
.list-group {
    border-radius: 5px;
    margin-bottom: 20px;
}

.list-group-item {
    padding: 12px 15px;
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

/* Link styles */
.schedule-link {
    text-decoration: none;
    color: #0d6efd;
    display: block;
    transition: color 0.2s ease;
}

.schedule-link:hover {
    text-decoration: underline;
    color: #0a58ca;
}

/* Alert styles */
.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Button styles */
.btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Scroll buttons */
.scroll-btn {
    position: fixed;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.scroll-btn:hover {
    transform: scale(1.05);
}

.scroll-up {
    bottom: 40px;
    right: 20px;
    background-color: #6c757d;
    color: white;
}

.scroll-down {
    bottom: 100px;
    right: 20px;
    background-color: #0d6efd;
    color: white;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    h1 {
        font-size: 1.5rem;
    }

    h2 {
        font-size: 1.3rem;
    }

    .schedule-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .task-item {
        flex-direction: column;
    }

    .task-time {
        width: 100%;
        margin-bottom: 5px;
    }
}
    </style>
</head>
<body>

@if(auth()->check())
    <nav style="background-color: #f8f9fa; padding: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Welcome, {{ auth()->user()->name }}</span>
        </div>
    </nav>
@endif

<div class="container py-4">
    <div class="btn-container mb-3">
        <a href="{{ url('index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <h1>My Volunteer Schedule</h1>

    @if(empty($all_schedules))
        <div class="alert alert-info">
            <p>You don't have any schedules assigned yet.</p>
        </div>
    @else
        <div>
            <h2>Available Schedules</h2>
            <ul class="list-group">
                @foreach($all_schedules as $schedule)
                    <li class="list-group-item">
                        <a 
                           href="{{ url()->current() }}?schedule_id={{ $schedule->volunteer_schedule_id }}" 
                           class="schedule-link"
                        >
                            {{ date('l, F j, Y', strtotime($schedule->date)) }} 
                            - {{ date('g:i A', strtotime($schedule->from_duration)) }} to {{ date('g:i A', strtotime($schedule->to_duration)) }} 
                            ({{ $schedule->is_active ? 'Active' : 'Inactive' }})
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($selected_schedule)
            <div class="schedule-card mt-4">
                <div class="schedule-header">
                    <div>
                        <span class="schedule-date">{{ date('l, F j, Y', strtotime($selected_schedule->date)) }}</span>
                        <span class="schedule-time">{{ date('g:i A', strtotime($selected_schedule->from_duration)) }} - {{ date('g:i A', strtotime($selected_schedule->to_duration)) }}</span>
                    </div>
                    <span class="schedule-status">{{ $selected_schedule->is_active ? 'Active' : 'Inactive' }}</span>
                </div>

                <div class="tasks-container mt-3">
                    <h3>Tasks</h3>
                    @if(!empty($tasks))
                        <ul class="task-list list-unstyled">
                            @foreach($tasks as $task)
                                <li class="task-item">
                                    <div class="task-time">
                                        {{ date('g:i A', strtotime($task->start_time)) }} - {{ date('g:i A', strtotime($task->end_time)) }}
                                    </div>
                                    <div class="task-details">
                                        {{ $task->task_details }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">
                            <p>No tasks assigned for this schedule.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
<!-- زر النزول لتحت -->
<button onclick="scrollOneStepDown()" class="btn btn-primary position-fixed" style="bottom: 100px; right: 20px;">
    ⬇️
</button>

<!-- زر الطلوع لفوق -->
<button onclick="scrollOneStepUp()" class="btn btn-secondary position-fixed" style="bottom: 40px; right: 20px;">
    ⬆️
</button>

<script>
    function scrollOneStepUp() {
        window.scrollBy({ top: -100, behavior: 'smooth' }); // خطوة لفوق
    }

    function scrollOneStepDown() {
        window.scrollBy({ top: 100, behavior: 'smooth' }); // خطوة لتحت
    }
</script>
</body>
</html>
