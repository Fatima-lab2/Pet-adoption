<!DOCTYPE html>
<html>
<head>
    <title>Animal Feeding</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
   
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Animal Feeding Schedules</h1>
        <a href="{{ route('create_feeding', ['animal_id' => $animal_id]) }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> Create New Schedule
</a>

            <a href="/index" class="btn btn-secondary">Back</a>
        </div>

      

       
        @foreach($animals as $animal_id => $animal)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">{{ $animal['animal_name'] }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $animal['image'] }}" alt="{{ $animal['animal_name'] }}" class="img-fluid rounded">
                            <p class="mt-2">
                                Status: <span class="badge bg-{{ $animal['status'] == 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($animal['status']) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-9">
                            @foreach($animal['schedules'] as $schedule_id => $schedule)
                                <div class="mb-4 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="h6 mb-0">
                                            Schedule #{{ $schedule_id }} - 
                                            Method: {{ $schedule['method'] }}, 
                                            Frequency: {{ $schedule['frequency'] }}
                                        </h4>
                                        <div>
                                            <a href="{{ route('edit_feeding', ['schedule_id' => $schedule_id, 'animal_id' => $animal_id]) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <h5 class="h6 mt-3">Assigned Foods:</h5>
                                    <div class="row">
                                        @foreach($schedule['foods'] as $food)
                                            <div class="col-md-4 mb-2">
                                                <div class="card">
                                                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                        <span>{{ $food['food_name'] }}</span>
                                                        <a href="{{ route('remove_food_from_schedule', ['schedule_id' => $schedule_id, 'food_id' => $food['food_id']]) }}" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3">
                                        <form method="POST" action="{{ route('add_food_to_schedule') }}" class="row g-2">
                                            @csrf
                                            <input type="hidden" name="schedule_id" value="{{ $schedule_id }}">
                                            <div class="col-md-8">
                                                <select name="food_id" class="form-select" required>
                                                    <option value="">Select Food to Add</option>
                                                    @foreach($foods as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-plus"></i> Add Food
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>