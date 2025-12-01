<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Contracts</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
</head>
<body>

    @include('partials.nav-bar')
 @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif
    <div class="container">
        <h2>Contracts for Dr. {{ ucfirst($doctor->name) }}</h2>

       
<div class="contract-container">
    @forelse($contracts as $contract)
        <div class="contract-box">
            <p><strong>Status:</strong> {{ ucfirst($contract->status) }}</p>
            <p><strong>Start Date:</strong> {{ $contract->start_date }}</p>
            <p><strong>End Date:</strong> {{ $contract->end_date }}</p>
            <p><strong>Salary/Hour:</strong> ${{ $contract->salary }}</p>
            <p><strong>Vacation Days/Month:</strong> {{ $contract->vacation_days }}</p>
            <p><strong>Contract Type:</strong> {{ ucfirst($contract->contract_type) }}</p>

            @if($contract->termination_date)
                <p><strong>Terminated On:</strong> {{ $contract->termination_date }}</p>
                <p><strong>Reason:</strong> {{ $contract->termination_reason }}</p>
            @else
                <div class="actions">
                    <a href="{{ url('/doctor_terminate_contract/' . $contract->contract_id) }}" class="btn btn-terminate" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">Terminate Contract</a>
                </div>
            @endif
        </div>
    @empty
        <p>No contracts found for this doctor.</p>
    @endforelse
</div>
            @role('employee')
            @if(Auth::user()?->employee?->department_id == 2)
            <div style="text-align: right; margin-bottom: 15px;margin-top:20px;">
            <a href="{{ url('/add_doctor_contract/' . $doctor->doctor_id) }}" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;"  class="btn btn-add">Add New Contract</a>
            </div>
            @endif
            @endrole
</body>
</html>
