<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Adoption Request</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (Optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<!-- Navbar -->
@include('partials.nav-bar')

<div class="container my-5">
    <h2 class="mb-4">Confirm Adoption Approval</h2>

    <!-- Adoption Request Information -->
    <div class="card shadow-sm">
        <div class="row g-3">
            <div class="col-md-4">
                <img src="{{ $adoption_request->image === 'images/animal.jpg' ? 
                            asset('images/animal.jpg') : 
                            asset('storage/' . $adoption_request->image) }}" 
                            class="img-fluid rounded" alt="Animal Image">
            </div>
            <div class="col-md-8">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Adopter Name:</strong> {{ ucfirst($adoption_request->name) }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $adoption_request->email }}</li>
                    <li class="list-group-item"><strong>Reason:</strong> {{ $adoption_request->reason }}</li>
                    <li class="list-group-item"><strong>Other Pets:</strong> {{ $adoption_request->other_pets }}</li>
                    <li class="list-group-item"><strong>Has Children:</strong> {{ $adoption_request->has_children }}</li>
                    <li class="list-group-item"><strong>Home Type:</strong> {{ $adoption_request->home_type }}</li>
                    <li class="list-group-item"><strong>Experience:</strong> {{ $adoption_request->experience }}</li>
                    <input type="hidden" name="user_id" value="{{ $adoption_request->user_id }}">
                    <input type="hidden" name="animal_id" value="{{ $adoption_request->animal_id }}">

                </ul>
            </div>
        </div>
    </div>

    <!-- Confirmation Section -->
    <div class="mt-4">
        <h4 class="text-warning" >Are you sure you want to approve this adoption request?</h4>
        <form method="POST" action="{{url('/approve_adoption/'. $adoption_request->id)}}">
            @csrf
            
            <div class="mt-3 d-flex justify-content-between gap-2">
                <!-- Approve Button -->
                <button type="submit" class="btn btn-success btn-lg w-48">
                    <i class="bi bi-check-circle me-1"></i> Confirm Approval
                </button> 
                <!-- Reject Button -->
                <a href="{{url('/adoptions')}}" class="btn btn-outline-danger btn-lg w-48">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
  
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
