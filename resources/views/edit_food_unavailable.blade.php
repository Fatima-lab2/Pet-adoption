<!DOCTYPE html>
<html>
<head>
    <title>Food Item Unavailable</title>
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
        .notice {
            padding: 20px;
            background-color: #f8f9fa;
            border-left: 5px solid #ffc107;
            margin-bottom: 20px;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Food Item Unavailable</h2>
        <div class="notice">
            <p>This food item is {{ $food->quantity <= 0 ? "out of stock" : "expired" }} and cannot be edited.</p>
            <p>Please add a new food item instead.</p>
        </div>
        <div class="btn-container">
            <a href="{{ route('manage_food') }}" class="btn btn-secondary">Back to Food Management</a>
            <a href="{{ route('add_food') }}" class="btn btn-primary">Add New Food</a>
        </div>
    </div>
</body>
</html>