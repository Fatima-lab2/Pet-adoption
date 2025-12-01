<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
/* Base container */
    .container {
        max-width: 800px;
        margin: 30px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Header styles */
    h2 {
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4e73df;
        font-size: 1.8rem;
    }

    /* Form group styling */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }

    /* Input and select fields */
    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="file"],
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: #4e73df;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Disabled room options */
    select option:disabled {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Button styles */
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn {
        background-color: #4e73df;
        color: white;
        margin-right: 10px;
    }

    .btn:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
    }

    /* Alert messages */
    .alert {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* File input styling */
    input[type="file"] {
        padding: 8px;
    }

    input[type="file"]::file-selector-button {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #f8f9fa;
        cursor: pointer;
        margin-right: 10px;
        transition: all 0.2s ease;
    }

    input[type="file"]::file-selector-button:hover {
        background-color: #e9ecef;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin: 15px;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
            margin-right: 0;
        }
    }

    /* Form validation styling */
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    /* Required field indicator */
    .required:after {
        content: " *";
        color: #dc3545;
    }
        </style>
    </head>
<body>
   
    
    <div class="container">
        <h2>Add Animal</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <form method="post" action="{{ route('store_animal') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="animal_name">Animal Name:</label>
                <input type="text" id="animal_name" name="animal_name" required>
            </div>
            
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Rabbit">Rabbit</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed">
            </div>
            
            <div class="form-group">
                <label for="birth_date">Birth Date:</label>
                <input type="date" id="birth_date" name="birth_date">
            </div>
            
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color">
            </div>
            
            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" id="weight" name="weight" step="0.01" min="0">
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="available">Available</option>
                    <option value="adopted">Adopted</option>
                    <option value="under_medical_care">Under Medical Care</option>
                    <option value="foster">Foster</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0">
            </div>
            
            <div class="form-group">
                <label for="arrival_date">Arrival Date:</label>
                <input type="date" id="arrival_date" name="arrival_date" required>
            </div>
            
            <div class="form-group">
                <label for="room_id">Room:</label>
                <select id="room_id" name="room_id">
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->room_id }}" 
                            @if(isset($room->available_slots) && $room->available_slots <= 0) disabled @endif>
                            {{ $room->name }} ({{ $room->category }}) - Available: {{ $room->available_slots ?? $room->capacity }}
                            @if(isset($room->available_slots) && $room->available_slots <= 0) (FULL) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="image">Animal Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="btn">Add Animal</button>
            <a href="{{ route('manage_animals') }}" class="btn">Back</a>
        </form>
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