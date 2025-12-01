<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    html, body {
        height: 100%;
        margin: 0;
    }
    
    body {
        display: flex;
        flex-direction: column;
    }
    
    .form-wrapper {
        width: 100%;
        max-width: 900px; /* Increased from 700px to make it wider */
        padding: 20px;
    }
    
    .card {
        height: 100%;
        margin-top: 0;
    }
    
    .card-body {
        overflow-y: auto; /* Adds scroll if content is too long */
    }
    
    main.container {
        flex: 1;
        padding: 20px 0;
    }
    .mt-2 img {
        max-width: 70px;
        height: auto;
        border-radius: 8px; /* اختياري للتدوير */
    }
</style>
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4 col-lg-6 form-wrapper">


                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">Edit Animal</h2>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('update_animal', $animal->animal_id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="animal_name" class="form-label">Animal Name *</label>
                                <input type="text" class="form-control" id="animal_name" name="animal_name" 
                                    value="{{ $animal->animal_name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="Dog" {{ $animal->type == 'Dog' ? 'selected' : '' }}>Dog</option>
                                    <option value="Cat" {{ $animal->type == 'Cat' ? 'selected' : '' }}>Cat</option>
                                    <option value="Rabbit" {{ $animal->type == 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                    <option value="Other" {{ $animal->type == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="breed" class="form-label">Breed *</label>
                                <input type="text" class="form-control" id="breed" name="breed" 
                                    value="{{ $animal->breed }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Birth Date *</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date"
                                    value="{{ $animal->birth_date }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" 
                                    value="{{ $animal->color }}">
                            </div>

                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control" id="weight" name="weight"
                                    value="{{ $animal->weight }}">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="available" {{ $animal->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="adopted" {{ $animal->status == 'adopted' ? 'selected' : '' }}>Adopted</option>
                                    <option value="under_medical_care" {{ $animal->status == 'under_medical_care' ? 'selected' : '' }}>Under Medical Care</option>
                                    <option value="foster" {{ $animal->status == 'foster' ? 'selected' : '' }}>Foster</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="room_id" class="form-label">Room *</label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->room_id }}" {{ $room->room_id == $animal->room_id ? 'selected' : '' }}>
                                           {{ $room->name }} - {{ $room->category }}
                                              </option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Animal Image</label>
                                <input type="file" class="form-control" id="image" name="image">

                                @if ($animal->image)
                                    <div class="mt-2">
                                        <p>Current Image:</p>
                                        <img src="{{ $animal->image === 'images/animal.jpg' 
                                                 ? asset('images/animal.jpg') 
                                                : asset('storage/' . $animal->image) }}" alt="Animal">


                                    </div>
                                @endif
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('manage_animals') }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Animal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
