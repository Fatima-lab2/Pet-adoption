
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adoption Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (Optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .adoption-card .card {
            border-radius: 12px;
            transition: transform 0.2s ease-in-out;
        }
        .adoption-card .card:hover {
            transform: scale(1.01);
        }
        .adoption-card img {
            max-height: 250px;
            object-fit: cover;
        }
        .list-group-item {
            border: none;
            padding: 0.5rem 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
@include('partials.nav-bar')
@if(session('status'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif
    <a href="#top" class="scroll-button" style="text-decoration: none">up</a>
<div class="container my-5">
    @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
   </div>
    @endif

    <div class="user-tab" style="margin-bottom: 25px;margin-top:0">
            <a href="#pending">Pending</a>
            <a href="#approved">Approved</a>
            <a href="#rejected">Rejected</a>
        </div>
    <!-- Pending Section -->
    <h4 class="text-warning mb-3">Pending Requests</h4>
    @foreach($pending as $request)
    <div class="adoption-card w-100 mb-4" id="pending">
        <div class="card shadow-sm p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <img src="{{ $request->image === 'images/animal.jpg' ?
                    asset('images/animal.jpg')
                    : asset('storage/' . $request->image) }}" class="img-fluid rounded" alt="Animal Image">
                </div>
                <div class="col-md-8">
                    <ul class="list-group list-group-flush" style="text-align: left">
                        <input type="hidden" name="user_id" value="{{ $request->user_id }}">
                        <li class="list-group-item"><strong style="color:#007bff">Adopter Name:</strong> {{ ucfirst($request->name) }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Pet Wanted:</strong> {{ ucfirst($request->animal_name) }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Email:</strong> {{ $request->email }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Status:</strong>  {{ $request->status }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Date:</strong>{{ \Carbon\Carbon::parse($request->date)->format('d-m-Y') }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Reason:</strong>  {{ $request->reason }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Other Pets:</strong> {{ $request->other_pets }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Has Children:</strong> {{ $request->has_children }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Home Type:</strong> {{ $request->home_type }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Experience:</strong>{{ $request->experience}}</li>
                    </ul>
                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                          <input type="hidden" name="animal_id" value="{{ $request->animal_id }}">
                        <div class="mt-3 d-flex justify-content-between gap-2">
                            <!-- Approve Button -->
                            <a href="{{ url('/approve_adoption/' . $request->id) }}" class="btn btn-success btn-sm flex-fill">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </a>    
                            <!-- Reject Button -->
                            <a href="{{ url('/reject_adoption_request/' . $request->id) }}" class="btn btn-outline-danger btn-sm flex-fill">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </a>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Approved Section -->
    <h4 class="text-success mb-3">Approved Requests</h4>
    @foreach($aproved as $request)
    <div class="adoption-card w-100 mb-4" id="approved">
        <div class="card shadow-sm p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <img src="{{ $request->image === 'images/animal.jpg' ?
                    asset('images/animal.jpg')
                    : asset('storage/' . $request->image) }}" class="img-fluid rounded" alt="Animal Image">
                </div>
                <div class="col-md-8" style="text-align: left">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong style="color:#007bff">Adopter Name:</strong> {{ ucfirst($request->name) }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Email:</strong> {{ $request->email }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Status:</strong>  {{ $request->status }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Date:</strong>{{ \Carbon\Carbon::parse($request->date)->format('d-m-Y')  }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Reason:</strong>  {{ $request->reason }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Other Pets:</strong> {{ $request->other_pets }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Has Children:</strong> {{ $request->has_children }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Home Type:</strong> {{ $request->home_type }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Experience:</strong> {{$request->experience}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    <!-- Rejected Section -->
    <h4 class="text-danger mb-3">Rejected Requests</h4>
    @foreach($rejected as $request)
    <div class="adoption-card w-100 mb-4" id="rejected">
        <div class="card shadow-sm p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <img src="{{ $request->image === 'images/animal.jpg' ?
                    asset('images/animal.jpg')
                    : asset('storage/' . $request->image) }}" class="img-fluid rounded" alt="Animal Image">
                </div>
                <div class="col-md-8" style="text-align: left">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong  style="color:#007bff">Adopter Name:</strong> {{ ucfirst($request->name) }}</li>
                        <li class="list-group-item"><strong  style="color:#007bff">Email:</strong> {{ $request->email }}</li>
                        <li class="list-group-item"><strong  style="color:#007bff">Status:</strong>  {{ $request->status }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Date:</strong>{{ \Carbon\Carbon::parse($request->date)->format('d-m-Y') }}/li>
                        <li class="list-group-item"><strong style="color:#007bff">Reason:</strong>  {{ $request->reason }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Other Pets:</strong> {{ $request->other_pets }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Has Children:</strong> {{ $request->has_children }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Home Type:</strong> {{ $request->home_type }}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Experience:</strong>{{ $request->experience}}</li>
                        <li class="list-group-item"><strong style="color:#007bff">Rejection Reason:</strong>{{ $request->rejection_reason}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endforeach


<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
