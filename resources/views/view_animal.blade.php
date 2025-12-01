<!DOCTYPE html>
<html>
<head>
    <title>{{ $animal->animal_name }} - Animal Details</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">    
     <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
/* styles.css - Animal Details Page */

/* Base container and layout */
.main-content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Animal header section */
.animal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.animal-header h1 {
    margin: 0;
    color: #333;
}

.animal-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* Card styles */
.card {
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.card-header {
    background-color: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.card-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #333;
}

.card-body {
    padding: 20px;
}

/* Animal details layout */
.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.col-md-4, .col-md-8 {
    padding: 0 15px;
    box-sizing: border-box;
}

.col-md-4 {
    flex: 0 0 33.333%;
    max-width: 33.333%;
}

.col-md-8 {
    flex: 0 0 66.666%;
    max-width: 66.666%;
}

/* Image styling */
.img-thumbnail {
    width: 100%;
    height: auto;
    max-width: 300px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px;
    background-color: #fff;
}

/* Detail text styling */
.card-body p {
    margin-bottom: 10px;
    font-size: 1rem;
    line-height: 1.6;
}

.card-body strong {
    font-weight: 600;
    color: #333;
}

/* Badge styling */
.badge {
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.badge-available {
    background-color: #d4edda;
    color: #155724;
}

.badge-adopted {
    background-color: #cce5ff;
    color: #004085;
}

.badge-under_medical_care {
    background-color: #fff3cd;
    color: #856404;
}

.badge-foster {
    background-color: #e2e3e5;
    color: #383d41;
}

/* Checkup card styles */
.checkup-card {
    border-left: 4px solid #17a2b8;
}

.checkup-card .card-header {
    background-color: rgba(23, 162, 184, 0.05);
}

.checkup-card h4 {
    margin: 0;
    font-size: 1.2rem;
}

/* Alert styles */
.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border-color: #bee5eb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffeeba;
}

.alert-link {
    color: #0056b3;
    text-decoration: underline;
    font-weight: 600;
}

/* Button styles */
.btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-info {
    background-color: #17a2b8;
    color: white;
}

.btn-info:hover {
    background-color: #138496;
}

/* Responsive design */
@media (max-width: 768px) {
    .animal-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .col-md-4, .col-md-8 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .col-md-4 {
        margin-bottom: 20px;
    }
    
    .card-header h2 {
        font-size: 1.3rem;
    }
    
    .checkup-card h4 {
        font-size: 1.1rem;
    }
}
        </style>
</head>
<body>
    

    <div class="container main-content">
        <div class="animal-header">
            <h1>{{ $animal->animal_name }}</h1>
            <div class="animal-actions">
                    @auth
                 @if(auth()->user()->hasAnyRole(['doctor']))
                    @if ($animal->health_record_id)
                        <a href="{{ route('create_checkup', $animal->animal_id) }}" class="btn btn-primary">New Checkup</a>
                    @else
                        <a href="{{ route('create_health_record', $animal->animal_id) }}" class="btn btn-primary">Create Health Record</a>
                    @endif
                @endif
                @endauth
                <a href="{{ route('search_animals') }}" class="btn btn-secondary">Back to home</a>
            </div>
        </div>
        
        <div class="animal-details card">
            <div class="card-header">
                <h2>Basic Information</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if ($animal->image)
                            <img src="{{ $animal->image === 'images/animal.jpg' 
                                                 ? asset('images/animal.jpg') 
                                                : asset('storage/' . $animal->image) }}" alt="Animal" style="width: 250px; height: 350px; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/default.jpg') }}" alt="No image available" class="img-thumbnail">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <p><strong>Type:</strong> {{ $animal->type }}</p>
                        <p><strong>Breed:</strong> {{ $animal->breed }}</p>
                        <p><strong>Age:</strong> {{ \Carbon\Carbon::parse($animal->birth_date)->age }} years</p>
                        <p><strong>Gender:</strong> {{ $animal->gender }}</p>
                        <p><strong>Color:</strong> {{ $animal->color }}</p>
                        <p><strong>Weight:</strong> {{ $animal->weight }} kg</p>
                        <p><strong>Status:</strong> 
                            <span class="badge badge-{{ strtolower($animal->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $animal->status)) }}
                            </span>
                        </p>
                        <p><strong>Room:</strong> {{ $animal->room_name ?? 'Not assigned' }} ({{ $animal->room_category ?? 'N/A' }})</p>
                        <p><strong>Arrival Date:</strong> {{ \Carbon\Carbon::parse($animal->arrival_date)->format('M j, Y') }}</p>
                        <p><strong>Price:</strong> ${{ number_format($animal->price, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if ($animal->health_record_id)
            <div class="health-record card mt-4">
                <div class="card-header">
                    <h2>Health Record</h2>
                </div>
                <div class="card-body">
                    <div class="health-record-info mb-4">
                        <p><strong>Primary Doctor:</strong> {{ $animal->doctor_name ?? 'Not assigned' }}</p>
                        <p><strong>Record Created:</strong> {{ \Carbon\Carbon::parse($animal->created_date)->format('M j, Y') }}</p>
                        
                          @auth
                  @if(auth()->user()->hasAnyRole(['doctor']))
                            <a href="{{ route('edit_health_record', $animal->health_record_id) }}" class="btn btn-warning">Edit Health Record</a>
                            <a href="{{ route('health_record', $animal->animal_id) }}" class="btn btn-info">View Full Health Record</a>
                        @endif
                        @endauth
                    </div>
                    
                    <h3>Recent Checkups</h3>
                    @if (count($checkups) > 0)
                        @foreach (array_slice($checkups, 0, 3) as $checkup)
                            <div class="checkup-card card mb-3">
                                <div class="card-header">
                                    <h4>Checkup on {{ \Carbon\Carbon::parse($checkup->checkup_date)->format('M j, Y') }}</h4>
                                </div>
                                <div class="card-body">
                                    <p><strong>Doctor:</strong> {{ $checkup->doctor_name }}</p>
                                    <p><strong>Details:</strong> {{ $checkup->details }}</p>
                                    <p><strong>Next Checkup:</strong> {{ $checkup->next_checkup ? \Carbon\Carbon::parse($checkup->next_checkup)->format('M j, Y') : 'Not scheduled' }}</p>
                                    
                                    @if ($checkup->medicines)
                                        <div class="alert alert-info mt-3">
                                            <h5>Medicines Administered:</h5>
                                            <p>{{ $checkup->medicines }}</p>
                                        </div>
                                    @endif
                                    
                                    @if ($checkup->vaccinations)
                                        <div class="alert alert-success mt-3">
                                            <h5>Vaccinations Given:</h5>
                                            <p>{{ $checkup->vaccinations }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if (count($checkups) > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('health_record', $animal->animal_id) }}" class="btn btn-secondary">View All Checkups ({{ count($checkups) }})</a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">No checkup records found.</div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-warning mt-4">
                No health record found for this animal.
                  @auth
                 @if(auth()->user()->hasAnyRole(['doctor']))
                    <a href="{{ route('create_health_record', $animal->animal_id) }}" class="alert-link">Create health record now</a>
                @endif
                @endauth
            </div>
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