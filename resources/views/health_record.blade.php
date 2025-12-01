<!DOCTYPE html>
<html>
<head>
    <title>Health Record for {{ $animal->animal_name }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .container { padding: 20px; }
        .animal-info { margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
        .health-record { margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px; padding: 15px; }
        .checkup-details { margin-bottom: 15px; }
        .medicine-list, .vaccination-list { margin-top: 10px; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.9em; 
            font-weight: bold;
        }
        .status-available { background-color: #d4edda; color: #155724; }
        .status-adopted { background-color: #cce5ff; color: #004085; } 
        .status-under_medical_care { background-color: #fff3cd; color: #856404; }
        .status-foster { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
  
    <div class="container">
        <h1>Health Record for {{ $animal->animal_name }}</h1>
        
        <div class="animal-info">
            <h2>Animal Information</h2>
            <p><strong>Type:</strong> {{ $animal->type }}</p>
            <p><strong>Breed:</strong> {{ $animal->breed }}</p>
            <p><strong>Age:</strong> {{ date_diff(date_create($animal->birth_date), date_create('today'))->y }} years</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ strtolower($animal->status) }}">
                    {{ ucfirst(str_replace('_', ' ', $animal->status)) }}
                </span>
            </p>
        </div>

        <div class="actions mb-3">
            <a href="{{ route('create_checkup', ['animal_id' => $animal_id]) }}" class="btn btn-primary">Add New Treatment</a>
            <a href="/index" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <h2>Medical History</h2>
        
        @if (count($health_records) > 0)
            @foreach ($health_records as $record)
                <div class="health-record">
                    @if ($record->checkup_id)
                        <div class="checkup-details">
                            <h4>Checkup on {{ date('M j, Y', strtotime($record->checkup_date)) }}</h4>
                            <p><strong>Doctor:</strong> {{ $record->doctor_name }}</p>
                            <p><strong>Details:</strong> {{ $record->details }}</p>
                            <p><strong>Next Checkup:</strong> {{ $record->next_checkup ? date('M j, Y', strtotime($record->next_checkup)) : 'Not scheduled' }}</p>
                            
                            @if ($record->medicines)
                                <div class="medicine-list">
                                    <h5>Medicines Administered:</h5>
                                    <p>{{ $record->medicines }}</p>
                                </div>
                            @endif
                            
                            @if ($record->vaccinations)
                                <div class="vaccination-list">
                                    <h5>Vaccinations Given:</h5>
                                    <p>{{ $record->vaccinations }}</p>
                                </div>
                            @endif

                            <a href="{{ route('edit_health_record', ['health_record_id' => $record->health_record_id]) }}" class="btn btn-warning">Edit Record</a>
                        </div>
                    @else
                        <p class="no-records">No treatments recorded for this health record.</p>
                    @endif
                </div>
            @endforeach
       @else
    <div class="alert alert-info">
        No health records found for this animal. 
        <a href="{{ route('create_checkup', ['animal_id' => $animal_id]) }}" class="btn btn-primary">
            Create first treatment record
        </a>
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