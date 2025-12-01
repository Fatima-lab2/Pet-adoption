<!DOCTYPE html>
<html>
<head>
    <title>Food Inventory Management</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
    <style>
        .no-results {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .expired {
            background-color: #ffcccc;
            color: #cc0000;
        }
        .out-of-stock {
            background-color: #fff3cd;
            color: #856404;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .back-to-top {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    
    <div class="container py-4">
        <h2>Food Inventory Management</h2>
        
        <div class="btn-container">
            <a href="#available" class="btn btn-primary">Available Food</a>
            <a href="#expired" class="btn btn-warning">Expired/Unavailable</a>
            <a href="{{ route('add_food') }}" class="btn btn-success">Add New Food</a>
            <a href="{{ url('index') }}" class="btn btn-secondary">Back To Dashboard</a>
        </div>

        <!-- Available Food Section -->
        <h3 id="available">Available Food Items</h3>
        @if(count($available_food) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Expires</th>
                        <th>Managed By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($available_food as $food)
                    <tr>
                        <td>{{ $food->food_id }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->type }}</td>
                        <td>{{ $food->description }}</td>
                        <td>{{ $food->quantity }}</td>
                        <td>{{ date('M d, Y', strtotime($food->expire_date)) }}</td>
                        <td>{{ $food->employee_name ?? 'Unassigned' }}</td>
                        <td>
                            <a href="{{ route('edit_food', $food->food_id) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="no-results">No available food items found.</div>
        @endif

        <div class="back-to-top">
            <button onclick="scrollToTop()" class="btn btn-secondary">Back to Top</button>
        </div>

        <!-- Expired/Unavailable Food Section -->
        <h3 id="expired">Expired/Unavailable Food Items</h3>
        @if(count($expired_food) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Expired On</th>
                        <th>Status</th>
                        <th>Managed By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expired_food as $food)
                    @php
                        $status = ($food->quantity <= 0) ? "Out of Stock" : "Expired";
                        $status_class = ($food->quantity <= 0) ? "out-of-stock" : "expired";
                    @endphp
                    <tr>
                        <td>{{ $food->food_id }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->type }}</td>
                        <td>{{ $food->description }}</td>
                        <td>{{ $food->quantity }}</td>
                        <td>{{ date('M d, Y', strtotime($food->expire_date)) }}</td>
                        <td><span class="status {{ $status_class }}">{{ $status }}</span></td>
                        <td>{{ $food->employee_name ?? 'Unassigned' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="no-results">No expired or unavailable food items found.</div>
        @endif

        <div class="back-to-top">
            <button onclick="scrollToTop()" class="btn btn-secondary">Back to Top</button>
        </div>
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
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
      function scrollOneStepUp() {
        window.scrollBy({ top: -100, behavior: 'smooth' }); // خطوة لفوق
    }

    function scrollOneStepDown() {
        window.scrollBy({ top: 100, behavior: 'smooth' }); // خطوة لتحت
    }
    </script>
</body>
</html>