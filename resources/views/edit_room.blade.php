<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <div class="container py-4">
        <a href="{{ route('room') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Rooms
        </a>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Edit Room</h2>
            </div>
            
            <div class="card-body">
                @if($is_over_capacity)
                    <div class="alert alert-warning mb-4">
                        <h5 class="alert-heading"> Overcapacity Warning</h5>
                        <p>This room currently has <strong>{{ $animal_count }}</strong> animals but its capacity is only <strong>{{ $room->capacity }}</strong>.</p>
                        <p class="mb-0">Please move animals to other rooms or increase the capacity below.</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('edit_room', $room->room_id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Room Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $room->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity *</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" 
                               min="{{ $min_capacity }}" max="30"
                               value="{{ old('capacity', $room->capacity) }}" required>
                        <div class="form-text">
                            Current animals: {{ $animal_count }}
                            @if($is_over_capacity)
                                <span class="text-danger">(Over capacity!)</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="Dogs - Female" {{ old('category', $room->category) == 'Dogs - Female' ? 'selected' : '' }}>Dogs - Female</option>
                            <option value="Dogs - Male" {{ old('category', $room->category) == 'Dogs - Male' ? 'selected' : '' }}>Dogs - Male</option>
                            <option value="Cat - Female" {{ old('category', $room->category) == 'Cat - Female' ? 'selected' : '' }}>Cat - Female</option>
                            <option value="Cat - Male" {{ old('category', $room->category) == 'Cat - Male' ? 'selected' : '' }}>Cat - Male</option>
                            <option value="Senior Dogs" {{ old('category', $room->category) == 'Senior Dogs' ? 'selected' : '' }}>Senior Dogs</option>
                            <option value="Senior Cats" {{ old('category', $room->category) == 'Senior Cats' ? 'selected' : '' }}>Senior Cats</option>
                            <option value="Pregnant Dogs" {{ old('category', $room->category) == 'Pregnant Dogs' ? 'selected' : '' }}>Pregnant Dogs</option>
                            <option value="Pregnant Cats" {{ old('category', $room->category) == 'Pregnant Cats' ? 'selected' : '' }}>Pregnant Cats</option>
                            <option value="Rabbits - Female" {{ old('category', $room->category) == 'Rabbits - Female' ? 'selected' : '' }}>Rabbits - Female</option>
                            <option value="Rabbits - Male" {{ old('category', $room->category) == 'Rabbits - Male' ? 'selected' : '' }}>Rabbits - Male</option>
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="availability" name="availability" 
                            {{ old('availability', $room->availability) ? 'checked' : '' }}>
                        <label class="form-check-label" for="availability">Available for new animals</label>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $room->description) }}</textarea>
                    </div>
                    
                    @if($animal_count > 0)
                    <div class="mb-3">
                        <label class="form-label">Current Animals</label>
                        <div class="animal-list bg-light p-2 rounded">
                            @foreach($animal_names as $name)
                                <span class="badge bg-primary me-1 mb-1">{{ $name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('room') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const capacity = parseInt(document.getElementById('capacity').value);
            const minCapacity = {{ $min_capacity }};
            
            if (capacity < minCapacity) {
                alert(`Capacity cannot be less than ${minCapacity} (current animal count).\n\nPlease move animals to other rooms first.`);
                e.preventDefault();
            } else if (capacity > 30) {
                alert('Maximum capacity is 30 animals per room.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>