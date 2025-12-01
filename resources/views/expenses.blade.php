<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
@include('partials.nav-bar')


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="container">
    <h2>All Expenses</h2>

    <a href="{{ url('/add_expense') }}" class="btn" style="display: inline-block; background-color: #3d3dd0; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s;">Add New Expense</a>

    @foreach($groupedExpenses as $month => $expenses)
        <h3 style="margin-top: 30px;">{{ \DateTime::createFromFormat('Y-m', $month)->format('F Y') }}</h3>
        <div class="expense-list">
            @foreach($expenses as $expense)
                <div class="expense-card">
                    <h3>{{ $expense->amount }} {{ $expense->currency }}</h3>
                    <p><strong >Spent For:{{ $expense->category }}</strong></p>
                    <p><strong>Date:</strong> {{ $expense->date }}</p>
                    <p><strong>Payment Method:</strong> {{ $expense->payment_method }}</p>
                    <p><strong>Details:</strong> {{ $expense->details }}</p>
                    <p><strong>Added By:</strong> {{ $expense->name }}</p>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

</div>
</body>
</html>
