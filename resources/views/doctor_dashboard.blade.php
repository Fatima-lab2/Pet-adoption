<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
<style>
/* Base container and layout styles */
.main-content {
    padding: 20px;
}

/* Quick links section */
.quick-links {
    margin-bottom: 20px;
}

/* Dashboard sections */
.dashboard-section {
    margin-bottom: 30px;
}

.section-header {
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

/* Table styles */
.styled-table {
    width: 100%;
    border-collapse: collapse;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.styled-table th {
    background-color: #f8f9fa;
    text-align: left;
}

.styled-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Status badge styles */
.status-badge {
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 0.9em;
    font-weight: bold;
}

.status-available {
    background-color: #d4edda;
    color: #155724;
}

.status-adopted {
    background-color: #cce5ff;
    color: #004085;
}

.status-under_medical_care {
    background-color: #fff3cd;
    color: #856404;
}

.status-foster {
    background-color: #e2e3e5;
    color: #383d41;
}

/* Responsive table container */
.table-responsive {
    overflow-x: auto;
}

/* Button styles (complementary to Bootstrap) */
.btn {
    transition: all 0.3s ease;
}

.btn-secondary {
    margin-right: 10px;
    margin-bottom: 10px;
}

.btn-primary {
    min-width: 100px;
}
    </style>
</head>
<body>

    <div class="container main-content">
        <h1>Animal Shelter Management System</h1>
        
        <div class="quick-links">
          
             <a href="index" class="btn btn-secondary">Back to animal</a>
        </div>
        
        <div class="dashboard-section">
            <h2 class="section-header">Upcoming Checkups</h2>
            @if (empty($upcoming_checkups))
                <p>No upcoming checkups found.</p>
            @else
                <div class="table-responsive">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Type</th>
                                <th>Checkup Date</th>
                                <th>Next Checkup</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcoming_checkups as $checkup)
                                <tr>
                                    <td>{{ $checkup->animal_name }}</td>
                                    <td>{{ $checkup->type }}</td>
                                    <td>{{ date('M j, Y', strtotime($checkup->checkup_date)) }}</td>
                                    <td>{{ date('M j, Y', strtotime($checkup->next_checkup)) }}</td>
                                    <td><a href="{{ route('health_record', ['animal_id' => $checkup->animal_id]) }}" class="btn btn-primary">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        <div class="dashboard-section">
            <h2 class="section-header">Animals Needing Attention</h2>
            @if (empty($animals_needing_attention))
                <p>No animals needing attention found.</p>
            @else
                <div class="table-responsive">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Type</th>
                                <th>Breed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($animals_needing_attention as $animal)
                                <tr>
                                    <td>{{ $animal->animal_name }}</td>
                                    <td>{{ $animal->type }}</td>
                                    <td>{{ $animal->breed }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $animal->status)) }}">
                                            {{ $animal->status }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('create_checkup', ['animal_id' => $animal->animal_id]) }}" class="btn btn-primary">Create Checkup</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
<!-- زر النزول لتحت -->
<button onclick="scrollOneStepDown()" class="btn btn-secondary position-fixed" style="bottom: 100px; right: 20px;">
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