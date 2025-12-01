<!DOCTYPE html>
<html>
<head>
    <title>Edit Treatment Record</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
    <div class="container">
        <h1>Edit Treatment Record for {{ $animal->animal_name }}</h1>

        <form action="{{ route('update_health_record', ['health_record_id' => $health_record_id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="details">Treatment Details</label>
                <textarea id="details" name="details" rows="10" class="form-control" required>{{ $checkup->details }}</textarea>
            </div>

            <div class="form-group">
                <label for="next_checkup">Next Checkup Date</label>
                <input type="date" id="next_checkup" name="next_checkup" class="form-control" 
                       value="{{ $checkup->next_checkup }}" required>
            </div>

            <h3>Medicines Administered</h3>
            @if (count($medicines) > 0)
                <ul>
                    @foreach ($medicines as $medicine)
                        <li>{{ $medicine->name }}</li>
                    @endforeach
                </ul>
            @else
                <p>No medicines recorded for this treatment.</p>
            @endif

            <h3>Vaccinations Given</h3>
            @if (count($vaccinations) > 0)
                <ul>
                    @foreach ($vaccinations as $vaccination)
                        <li>{{ $vaccination->name }}</li>
                    @endforeach
                </ul>
            @else
                <p>No vaccinations recorded for this treatment.</p>
            @endif

            <button type="submit" class="btn btn-success">Save Changes</button>
            <a href="{{ route('health_record', ['animal_id' => $animal->animal_id]) }}" class="btn btn-secondary">Back to Health Record</a>
        </form>
    </div>
</body>
</html>