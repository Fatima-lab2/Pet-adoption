<!DOCTYPE html>
<html>
<head>
    <title>Edit Feeding Schedule</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
  
    <div class="container py-4">
        <a href="{{ route('animal_food') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Feeding Schedules
        </a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h2>Edit Feeding Schedule for {{ $feeding->animal_name }}</h2>

        <form method="POST" action="{{ route('edit_feeding', ['schedule_id' => $feeding->feeding_schedule_id, 'animal_id' => $feeding->animal_id]) }}">
            @csrf
            <div class="mb-3">
                <label for="method" class="form-label">Feeding Method *</label>
                <input type="text" class="form-control" id="method" name="method" value="{{ old('method', $feeding->method) }}" required>
            </div>

            <div class="mb-3">
                <label for="frequency" class="form-label">Frequency *</label>
                <input type="text" class="form-control" id="frequency" name="frequency" value="{{ old('frequency', $feeding->frequency) }}" required>
            </div>

            <h3 class="mt-4 mb-3">Food Details</h3>
            <div class="mb-3">
                <label class="form-label">Available Food Items *</label>
                <div class="row">
                    @foreach($foods as $id => $name)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="foods[]" 
                                       value="{{ $id }}" id="food_{{ $id }}" 
                                       {{ in_array($id, $selected_food_ids) ? 'checked' : '' }}>
                                <label class="form-check-label" for="food_{{ $id }}">
                                    {{ $name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update Feeding Schedule
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>