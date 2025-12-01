<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee Contract</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

@include('partials.nav-bar')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="container">
    <h2>Add New Contract for {{ ucfirst($employee->name) }}</h2>

    <form method="POST" action="{{ url('/add_employee_contract/'. $employee->employee_id) }}">
        @csrf

        <label>Start Date:</label>
        <input type="date" name="start_date" required>

        <label>End Date:</label>
        <input type="date" name="end_date" required>

        <label>Salary (per hour):</label>
        <input type="number" name="salary" required >

        <label>Vacation Days per Month:</label>
        <input type="number" name="vacation_days" required>

        <label>Contract Type:</label>
        <select name="contract_type" required>
            <option value="full-time">Full-Time</option>
            <option value="part-time">Part-Time</option>
        </select>

        <label>Violation penalty:</label>
        <input type="text" name="violation_penalty" required >

        <input type="hidden" name="status" value="active">
        <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">


        <button type="submit" class="btn-submit">Create Contract</button>
    </form>
</div>

</body>
</html>
