<!DOCTYPE html>
<html>
<head>
    <title>Create Feeding Schedule</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
   
    <div class="container py-4">
        <a href="{{ route('animal_food', ['animal_id' => $animal->animal_id]) }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Feeding Schedules 
        </a>

        <h2>Create New Feeding Schedule for {{ $animal->animal_name }}</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('create_feeding', ['animal_id' => $animal->animal_id]) }}">
            @csrf
            <input type="hidden" name="animal_id" value="{{ $animal->animal_id }}">

            <div class="mb-3">
                <label for="method" class="form-label">Feeding Method *</label>
                <input type="text" class="form-control" id="method" name="method" value="{{ old('method') }}" required>
            </div>

            <div class="mb-3">
                <label for="frequency" class="form-label">Frequency and Time *</label>
                <input type="text" class="form-control" id="frequency" name="frequency" value="{{ old('frequency') }}" required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date *</label>
                <input type="date" class="form-control" id="start_date" name="start_date" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">End Date *</label>
                <input type="date" class="form-control" id="end_date" name="end_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('end_date') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Food Items *</label>
                <div class="row">
                    @foreach($foods as $id => $name)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="foods[]" value="{{ $id }}" id="food_{{ $id }}" {{ in_array($id, old('foods', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="food_{{ $id }}">
                                    {{ $name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Create Feeding Schedule
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>