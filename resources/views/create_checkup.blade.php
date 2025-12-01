<!DOCTYPE html>
<html>
<head>
    <title>Create Treatment for {{ $animal->animal_name }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .container { padding: 20px; }
        .table-danger { background-color: #f8d7da; }
        .table-warning { background-color: #fff3cd; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status-available { background-color: #d4edda; color: #155724; }
        .status-adopted { background-color: #cce5ff; color: #004085; }
        .status-under_medical_care { background-color: #fff3cd; color: #856404; }
        .status-foster { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
 
    <div class="container">
        <h1>Create Treatment for {{ $animal->animal_name }}</h1>

        @if (session('error'))
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h3>Animal Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Type:</strong> {{ $animal->type }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Breed:</strong> {{ $animal->breed }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Age:</strong> {{ date_diff(date_create($animal->birth_date), date_create('today'))->y }} years</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Current Status:</strong> 
                            <span class="badge badge-{{ strtolower($animal->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $animal->status)) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('store_checkup', ['animal_id' => $animal->animal_id]) }}">
            @csrf
            <div class="form-group">
                <label>Update Status:</label>
                <select name="status" class="form-control">
                    <option value="available" {{ $animal->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="adopted" {{ $animal->status == 'adopted' ? 'selected' : '' }}>Adopted</option>
                    <option value="under_medical_care" {{ $animal->status == 'under_medical_care' ? 'selected' : '' }}>Under medical care</option>
                    <option value="foster" {{ $animal->status == 'foster' ? 'selected' : '' }}>Foster</option>
                </select>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2>Treatment Details</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Notes:</label>
                        <textarea name="details" rows="5" class="form-control" required></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2>Prescribe Medicines</h2>
                </div>
                <div class="card-body">
                    @php
                        $has_expired = false;
                        foreach ($medicines as $m) {
                            if ($m->is_expired) $has_expired = true;
                        }
                    @endphp
                    
                    @if ($has_expired)
                    <div class="alert alert-warning">
                        <strong>Note:</strong> Expired medicines are highlighted in red and cannot be prescribed
                    </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Stock</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th>Prescribe</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicines as $medicine)
                                <tr class="{{ $medicine->is_expired ? 'table-danger' : ($medicine->is_low_stock ? 'table-warning' : '') }}">
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->quantity_in_stock }}</td>
                                    <td>{{ date('M d, Y', strtotime($medicine->expire_date)) }}</td>
                                    <td>
                                        @if ($medicine->is_expired)
                                            <span class="text-danger">Expired</span>
                                        @elseif ($medicine->is_low_stock)
                                            <span class="text-warning">Low Stock</span>
                                        @else
                                            <span class="text-success">OK</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($medicine->is_expired)
                                            <span class="text-muted">N/A</span>
                                        @else
                                            <input type="checkbox" name="medicines[{{ $medicine->medicine_id }}][prescribed]">
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="medicines[{{ $medicine->medicine_id }}][dosage]" class="form-control" 
                                               {{ $medicine->is_expired ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="medicines[{{ $medicine->medicine_id }}][frequency]" class="form-control"
                                               {{ $medicine->is_expired ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="medicines[{{ $medicine->medicine_id }}][details]" class="form-control"
                                               {{ $medicine->is_expired ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2>Administer Vaccinations</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vaccination</th>
                                    <th>Administer</th>
                                    <th>Dosage</th>
                                    <th>Notes</th>
                                    <th>Allergy Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaccinations as $vaccination)
                                <tr>
                                    <td>{{ $vaccination->name }}</td>
                                    <td><input type="checkbox" name="vaccinations[{{ $vaccination->vaccination_id }}][administered]"></td>
                                    <td><input type="text" name="vaccinations[{{ $vaccination->vaccination_id }}][dosage]" class="form-control"></td>
                                    <td><input type="text" name="vaccinations[{{ $vaccination->vaccination_id }}][details]" class="form-control"></td>
                                    <td><input type="text" name="vaccinations[{{ $vaccination->vaccination_id }}][allergy]" class="form-control"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Treatment</button>
                <a href="{{ route('health_record', ['animal_id' => $animal->animal_id]) }}" class="btn btn-secondary">Cancel</a>
            </div>
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