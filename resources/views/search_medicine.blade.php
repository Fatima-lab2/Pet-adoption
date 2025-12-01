<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>

        h2 {
            color: #0056b3;
            text-align: center;
        }

        .search-query {
            font-style: italic;
            color: #555;
            margin-bottom: 20px;
        }

        .medicine-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .medicine-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .medicine-card h4 {
            margin: 0 0 10px;
            color: #0056b3;
        }

        .medicine-card p {
            margin: 0;
            color: #666;
        }

        .no-results {
            color: red;
            font-weight: bold;
        }
        strong{
            color:#0056b3;
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
     <!-- Navbar -->
     @include('partials.nav-bar')

    <h2>Search Results</h2>
    <div class="search-query">You searched for: "{{ $query }}"</div>

    @if(count($medicines) > 0)
        <div class="medicine-list">
            @foreach($medicines as $medicine)
                <div class="medicine-card">
                    <img src="{{ $medicine->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $medicine->picture) }}" alt="">
                    <h4>{{ $medicine->name }}</h4>
                    <strong>Description:</strong><p>{{ $medicine->description }}</p>
                    <strong>Details:</strong><p>{{ $medicine->details }}</p>
                    <strong>Quantity_in_stock:</strong><p>{{ $medicine->quantity_in_stock }}</p>
                    <strong>Price:</strong><p>{{ $medicine->price }}</p>
                    <strong>Expire Date:</strong><p>{{ $medicine->expire_date }}</p>
                    <strong>Availability: </strong><p>{{ $medicine->is_available == 1 ? 'Available' : 'Not Available' }}</p>

                </div>
            @endforeach
        </div>
    @else
        <p class="no-results">No medicines found.</p>
    @endif
</body>
</html>
