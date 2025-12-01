<!DOCTYPE html>
<html>
<head>
    <title>Doctor Schedule</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f7fa;
    margin: 0;
    padding: 20px;
}

.tab-container {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.tab-buttons {
    display: flex;
    border-bottom: 1px solid #ddd;
    background-color: #f8f9fa;
}

.tab-button {
    padding: 12px 20px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #555;
    transition: all 0.3s ease;
}

.tab-button:hover {
    color: #2c7be5;
    background-color: rgba(44, 123, 229, 0.1);
}

.tab-button.active {
    color: #2c7be5;
    border-bottom: 3px solid #2c7be5;
    background-color: white;
}

.tab-content {
    display: none;
    padding: 20px;
}

.tab-content.active {
    display: block;
}

h2 {
    color: #2c3e50;
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.schedule-day {
    margin-bottom: 25px;
    border: 1px solid #e0e6ed;
    border-radius: 6px;
    overflow: hidden;
}

.schedule-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #f8fafc;
    border-bottom: 1px solid #e0e6ed;
}

.schedule-date {
    font-weight: 600;
    color: #2c3e50;
    flex: 2;
}

.schedule-time, .schedule-status {
    flex: 1;
    text-align: center;
}

.schedule-status {
    font-weight: 600;
}

.appointments-list {
    padding: 0;
}

.appointment-item {
    display: flex;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f4f8;
    transition: background-color 0.2s;
}

.appointment-item:hover {
    background-color: #f8fafc;
}

.appointment-time {
    flex: 1;
    color: #5c6b7a;
    min-width: 120px;
}

.appointment-animal {
    flex: 3;
}

.appointment-animal strong a {
    color: #2c7be5;
    text-decoration: none;
}

.appointment-animal strong a:hover {
    text-decoration: underline;
}

.appointment-details {
    margin-top: 5px;
    color: #5c6b7a;
    font-size: 14px;
}

.no-appointments {
    padding: 20px;
    text-align: center;
    color: #99a4b2;
    font-style: italic;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    margin-left: 8px;
}

.badge-dog {
    background-color: #8e44ad;
}

.badge-cat {
    background-color: #3498db;
}

.badge-rabbit {
    background-color: #e67e22;
}

.badge-other {
    background-color: #95a5a6;
}

.checkups-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.checkups-table th, .checkups-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e0e6ed;
}

.checkups-table th {
    background-color: #f8fafc;
    color: #2c3e50;
    font-weight: 600;
}

.checkups-table tr:hover {
    background-color: #f8fafc;
}

.navigation-buttons {
    max-width: 1200px;
    margin: 20px auto;
    text-align: right;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #2c7be5;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    transition: background-color 0.2s;
}

.btn:hover {
    background-color: #1a68d1;
}

.btn-primary {
    background-color: #2c7be5;
}

.btn-primary:hover {
    background-color: #1a68d1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .schedule-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .schedule-date, .schedule-time, .schedule-status {
        text-align: left;
        margin-bottom: 5px;
        width: 100%;
    }
    
    .appointment-item {
        flex-direction: column;
    }
    
    .appointment-time {
        margin-bottom: 10px;
    }
    
    .checkups-table {
        display: block;
        overflow-x: auto;
    }
}
       
        .schedule-list {
            margin-bottom: 20px;
        }
        .schedule-item {
            padding: 15px;
            border: 1px solid #e0e6ed;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .schedule-item:hover {
            background-color: #f8fafc;
        }
        .schedule-item.active {
            background-color: #e6f0ff;
            border-color: #2c7be5;
        }
        .schedule-details {
            display: none;
        }
        .schedule-details.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="tab-container">
        <div class="tab-buttons">
            <button class="tab-button active" onclick="openTab('schedule')">My Schedule & Appointments</button>
            <button class="tab-button" onclick="openTab('checkups')">Upcoming Checkups</button>
        </div>
        
        <!-- Schedule Tab -->
        <div id="schedule" class="tab-content active">
            <h2>My Schedule & Appointments</h2>
            
            @if (empty($organizedSchedules))
                <p>No schedules found.</p>
            @else
                <div class="schedule-list">
                    @foreach ($organizedSchedules as $date => $schedule)
                        <div class="schedule-item" onclick="showScheduleDetails('{{ $date }}')">
                            <div class="d-flex justify-content-between">
                                <div class="schedule-date">
                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                </div>
                                <div class="schedule-time">
                                    {{ \Carbon\Carbon::parse($schedule['schedule_info']->from_duration)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($schedule['schedule_info']->to_duration)->format('g:i A') }}
                                </div>
                                <div class="schedule-status">
                                    {{ $schedule['schedule_info']->is_active ? 'Active' : 'Inactive' }}
                                </div>
                            </div>
                        </div>
                        
                        <div id="schedule-{{ $date }}" class="schedule-details">
                            <div class="schedule-day">
                                <div class="schedule-header">
                                    <div class="schedule-date">
                                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                    </div>
                                    <div class="schedule-time">
                                        {{ \Carbon\Carbon::parse($schedule['schedule_info']->from_duration)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($schedule['schedule_info']->to_duration)->format('g:i A') }}
                                    </div>
                                    <div class="schedule-status">
                                        {{ $schedule['schedule_info']->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </div>
                                
                                <div class="appointments-list">
                                    @if (empty($schedule['appointments']))
                                        <div class="no-appointments">No appointments scheduled for this day</div>
                                    @else
                                        @foreach ($schedule['appointments'] as $appt)
                                            <div class="appointment-item">
                                                <div class="appointment-time"> 
                                                    {{ \Carbon\Carbon::parse($appt['start_time'])->format('g:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($appt['end_time'])->format('g:i A') }}
                                                </div>
                                                <div class="appointment-animal">
                                                    <strong>
                                                        <a href="{{ route('view_animal', $appt['animal_id']) }}">
                                                            {{ $appt['animal_name'] }}
                                                        </a>
                                                    </strong>
                                                    <span class="badge badge-{{ strtolower($appt['animal_type']) }}">
                                                        {{ $appt['animal_type'] }}
                                                    </span>
                                                    <div class="appointment-details">
                                                        {{ $appt['appointment_details'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Checkups Tab -->
        <div id="checkups" class="tab-content">
            <h2>Upcoming Checkups</h2>
            
            <table class="checkups-table">
                <tr>
                    <th>Animal</th>
                    <th>Type</th>
                    <th>Checkup Date</th>
                    <th>Next Checkup</th>
                    <th>Details</th>
                </tr>
                @if (empty($checkups))
                    <tr>
                        <td colspan="5" style="text-align: center;">No upcoming checkups</td>
                    </tr>
                @else
                    @foreach ($checkups as $c)
                        <tr>
                            <td>{{ $c->animal_name }}</td>
                            <td>
                                @php
                                    $typeClass = 'badge-other';
                                    if (strtolower($c->type) === 'dog') $typeClass = 'badge-dog';
                                    elseif (strtolower($c->type) === 'cat') $typeClass = 'badge-cat';
                                    elseif (strtolower($c->type) === 'rabbit') $typeClass = 'badge-rabbit';
                                @endphp
                                <span class="badge {{ $typeClass }}">
                                    {{ $c->type }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($c->checkup_date)->format('M j, Y') }}</td>
                            <td>{{ $c->next_checkup ? \Carbon\Carbon::parse($c->next_checkup)->format('M j, Y') : 'Not scheduled' }}</td>
                            <td>{{ $c->details }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <div class="navigation-buttons">
        <a href="{{ url('/index') }}" class="btn btn-primary">Back to animals</a>
    </div>

    <!-- Scroll buttons -->
    <button onclick="scrollOneStepDown()" class="btn btn-primary position-fixed" style="bottom: 100px; right: 20px;">
        ⬇️
    </button>
    <button onclick="scrollOneStepUp()" class="btn btn-secondary position-fixed" style="bottom: 40px; right: 20px;">
        ⬆️
    </button>

    <script>
        function openTab(tabName) {
            var tabContents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }

            var tabButtons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            event.currentTarget.classList.add("active");

            history.pushState(null, null, '?tab=' + tabName);
        }

        function showScheduleDetails(date) {
            // Hide all schedule details
            var allDetails = document.querySelectorAll('.schedule-details');
            allDetails.forEach(function(detail) {
                detail.classList.remove('active');
            });
            
            // Remove active class from all schedule items
            var allItems = document.querySelectorAll('.schedule-item');
            allItems.forEach(function(item) {
                item.classList.remove('active');
            });
            
            // Show selected schedule details
            var detail = document.getElementById('schedule-' + date);
            if (detail) {
                detail.classList.add('active');
                // Add active class to clicked item
                event.currentTarget.classList.add('active');
                // Scroll to the details
                detail.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (tabParam && (tabParam === 'schedule' || tabParam === 'checkups')) {
                openTab(tabParam);
            }
        };

        function scrollOneStepUp() {
            window.scrollBy({ top: -100, behavior: 'smooth' });
        }

        function scrollOneStepDown() {
            window.scrollBy({ top: 100, behavior: 'smooth' });
        }
    </script>
</body>
</html>