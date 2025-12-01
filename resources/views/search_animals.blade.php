<!DOCTYPE html>
<html>
<head>
    <title>Search Animals</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">    
     <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
.main-content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Search form styles */
.search-form {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

/* Button styles */
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-view {
    background-color: #17a2b8;
    color: white;
}

.btn-view:hover {
    background-color: #138496;
}

.btn-back {
    display: inline-block;
    margin-top: 20px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
}

.btn-back:hover {
    background-color: #5a6268;
}

/* Table styles (shared with doctor dashboard) */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

.styled-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.styled-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.styled-table tr:hover {
    background-color: #f1f1f1;
}

/* Status badge styles (shared with doctor dashboard) */
.status-badge {
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 0.9em;
    font-weight: bold;
    display: inline-block;
}

.status-available {
    background-color: #d4edda;
    color: #155724;
}

.status-adopted {
    background-color: #cce5ff;
    color: #004085;
}

.status-under-medical-care {
    background-color: #fff3cd;
    color: #856404;
}

.status-foster {
    background-color: #e2e3e5;
    color: #383d41;
}

/* Alert and message styles */
.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Responsive design */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
    .search-form {
        padding: 15px;
    }
    
    .form-control {
        font-size: 14px;
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 14px;
    }
    
    .styled-table th,
    .styled-table td {
        padding: 8px 10px;
        font-size: 14px;
    }
}
</style>

</head>
<body>
    
   

    <div class="container main-content">
        <h1>Search Animals</h1>
        
        
        <form method="get" class="search-form">
            <div class="form-group">
                <input type="text" name="search" placeholder="Search term" 
                       value="{{ request('search', '') }}" class="form-control">
            </div>
            
            <div class="form-group">
                <select name="search_type" class="form-control">
                    <option value="all" {{ request('search_type', '') == 'all' ? 'selected' : '' }}>All Fields</option>
                    <option value="name" {{ request('search_type', '') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="type" {{ request('search_type', '') == 'type' ? 'selected' : '' }}>Type</option>
                    <option value="breed" {{ request('search_type', '') == 'breed' ? 'selected' : '' }}>Breed</option>
                </select>
            </div>
            
            <div class="form-group">
                <select name="gender" class="form-control">
                    <option value="">All Genders</option>
                    <option value="Male" {{ request('gender', '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender', '') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
           
        @if ($search_performed)
            <div class="search-results">
                <h2>Search Results</h2>
                @if (empty($search_results))
                    <div class="alert alert-info">No animals found matching your search.</div>
                @else
                    <div class="table-responsive">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Breed</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($search_results as $animal)
                                <tr>
                                    <td>{{ $animal->animal_name }}</td>
                                    <td>{{ $animal->type }}</td>
                                    <td>{{ $animal->breed }}</td>
                                    <td>{{ $animal->gender ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($animal->birth_date)->age }} years</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace('_', '-', $animal->status)) }}">
                                            {{ ucfirst(str_replace('_', ' ', $animal->status)) }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('view_animal', $animal->animal_id) }}" class="btn btn-view">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
        
       <a href="{{ url('/index') }}" class="btn btn-primary">Back to animals</a>
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