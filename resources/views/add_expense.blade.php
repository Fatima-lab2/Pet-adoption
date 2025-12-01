<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')

<div class="container">
    <h2>Add New Expense</h2>

    <form method="POST" action="{{ url('/add_expense') }}" class="form-box">
        @csrf

        <label>Currency:</label>
        <select name="currency" required>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="LBP">LBP</option>
        </select>
        <label>Amount:</label>
        <input type="number" name="amount" required>

        <label>Category:</label>
        <input type="text" name="category" required>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Details:</label>
        <textarea name="details" required></textarea>

        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>

        <button type="submit" class="btn-submit">Add Expense</button>
    </form>
</div>
</body>
</html>
