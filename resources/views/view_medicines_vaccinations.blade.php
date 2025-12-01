<!DOCTYPE html>
<html>
<head>
    <title>Medical Inventory Overview</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">    
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* styles.css - Medical Inventory Overview */

/* Base container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Page header */
h1 {
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center;
    font-size: 2rem;
}

/* Inventory sections */
.inventory-section {
    margin-bottom: 40px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
}

.section-title {
    color: #3498db;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 1.5rem;
}

/* Tables */
.inventory-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.inventory-table th {
    background-color: #f8f9fa;
    color: #2c3e50;
    font-weight: 600;
    text-align: left;
    padding: 12px 15px;
    border-bottom: 2px solid #dee2e6;
}

.inventory-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: top;
}

.inventory-table tr:hover {
    background-color: #f8f9fa;
}

/* Status badges */
.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.expired {
    background-color: #f8d7da;
    color: #721c24;
}

.status-badge.low-stock {
    background-color: #fff3cd;
    color: #856404;
}

/* Responsive tables */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Button styles */
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    h1 {
        font-size: 1.5rem;
    }
    
    .section-title {
        font-size: 1.2rem;
    }
    
    .inventory-table th, 
    .inventory-table td {
        padding: 8px 10px;
        font-size: 0.9rem;
    }
    
    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}

/* Zebra striping for better readability */
.inventory-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Highlight important information */
.inventory-table td:contains('$') {
    font-weight: 600;
    color: #27ae60;
}

/* Expiry date warning */
.inventory-table tr:has(.status-badge.expired) {
    background-color: #fff5f5;
}

.inventory-table tr:has(.status-badge.expired):hover {
    background-color: #ffecec;
}
</style>
</head>
<body>
  
    <div class="container">
        <h1>Medical Inventory Overview</h1>
         <div class="text-center mt-4">
           <a href="{{ url('/index') }}" class="btn btn-primary">Back to animals</a>

        </div>
        <!-- Medicines Section -->
        <div class="inventory-section">
            <h2 class="section-title">Medicines</h2>
            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Stock</th>
                            <th>Expiry</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $med)
                            @php
                                $isExpired = $med->expire_date && \Carbon\Carbon::parse($med->expire_date)->isPast();
                                $isLowStock = $med->quantity !== null && $med->quantity < 5;
                            @endphp
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->description }}</td>
                            <td>{{ $med->quantity ?? 'N/A' }}</td>
                            <td>{{ $med->expire_date ? \Carbon\Carbon::parse($med->expire_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $med->price ? '$'.number_format($med->price, 2) : 'N/A' }}</td>
                            <td>
                                @if ($isExpired)
                                    <span class="status-badge expired">Expired</span>
                                @elseif ($isLowStock)
                                    <span class="status-badge low-stock">Low Stock</span>
                                @else
                                    <span>OK</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vaccinations Section -->
        <div class="inventory-section">
            <h2 class="section-title">Vaccinations</h2>
            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Symptoms</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vaccinations as $vac)
                        <tr>
                            <td>{{ $vac->name }}</td>
                            <td>{{ $vac->description }}</td>
                            <td>{{ $vac->symptoms }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
             <a href="{{ url('/index') }}" class="btn btn-primary">Back to animals</a>
        </div>
    </div>
 <!-- زر النزول لتحت -->
<button onclick="scrollStepDown()" class="btn btn-primary position-fixed bottom-25 end-0 m-3">
    ⬇️
</button>

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
