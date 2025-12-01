<!DOCTYPE html>
<html>
<head>
    <title>Edit Food Item</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
        .success {
            color: #28a745;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <h2>Edit Food Item</h2>
        <form method="post" action="{{ route('edit_food', $food->food_id) }}">
            @csrf
            <div class="form-group">
                <label for="name">Food Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $food->name) }}" required>
            </div>
            
            <div class="form-group">
                <label for="type">Type:</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="Dry" {{ old('type', $food->type) == 'Dry' ? 'selected' : '' }}>Dry</option>
                    <option value="Wet" {{ old('type', $food->type) == 'Wet' ? 'selected' : '' }}>Wet</option>
                    <option value="Raw" {{ old('type', $food->type) == 'Raw' ? 'selected' : '' }}>Raw</option>
                    <option value="Supplement" {{ old('type', $food->type) == 'Supplement' ? 'selected' : '' }}>Supplement</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $food->description) }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="{{ old('quantity', $food->quantity) }}" required>
            </div>
            
            <div class="form-group">
                <label for="expire_date">Expiration Date:</label>
                <input type="date" id="expire_date" name="expire_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('expire_date', $food->expire_date) }}" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Food</button>
                <a href="{{ route('manage_food') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>