<!DOCTYPE html>
<html>
<head>
    <title>Create Health Record for {{ $animal->animal_name }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
    <div class="container">
        <h1>Create Health Record for {{ $animal->animal_name }}</h1>
        
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('store_health_record', ['animal_id' => $animal->animal_id]) }}">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h3>Animal Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $animal->animal_name }}</p>
                    <p><strong>Type:</strong> {{ $animal->type }}</p>
                    <p><strong>Breed:</strong> {{ $animal->breed }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="details">Initial Health Notes</label>
                <textarea id="details" name="details" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Health Record</button>
            <a href="{{ url('/index') }}" class="btn btn-secondary">Cancel</a>

        </form>
    </div>
</body>
</html>