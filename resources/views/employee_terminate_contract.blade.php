<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terminate Contract</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

@include('partials.nav-bar')

<div class="container">
    <h2>Terminate Contract for  {{ ucfirst($employee->name) }}</h2>

    <form method="POST" action="{{ url('/employee_terminate_contract/' . $contract->contract_id) }}">
        @csrf
        <label>Termination Date:</label>
        <input type="date" name="termination_date" required>

        <label>Reason for Termination:</label>
        <textarea name="termination_reason" rows="4" required></textarea>

        <button type="submit" class="btn-submit">Terminate Contract</button>
    </form>
</div>

</body>
</html>
