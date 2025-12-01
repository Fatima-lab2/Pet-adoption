<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Donations</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

@include('partials.nav-bar')

<div class="container">
    <h2 class="page-title">Manage Donations</h2>
    <a href="#top" class="scroll-button">^</a>
    <p>
        The month with the highest donations total is:
        @if($highest_month && $highest_total)
            <strong>{{ $highest_month }}</strong> with <strong>${{ $highest_total }}</strong>
        @endif
    </p>

    <!-- Donations Grouped By Month -->
    <div class="month-group">
        <div class="donation-grid">
            @foreach($grouped_donations as $monthYear => $donations)
                <h2>{{ $monthYear }}</h2>
                <div class="donation-list">
                    @foreach($donations as $donation)
                    <div class="donation-card">
                        <p><strong style="color:#0056b3">Donor Name:</strong> {{ ucfirst($donation->name) }}</p>
                        <p><strong style="color:#0056b3">Email:</strong> {{ $donation->email }}</p>
                        <p><strong style="color:#0056b3">Phone Number:</strong> {{ $donation->phone_number }}</p>
                        <p><strong style="color:#0056b3">Amount:</strong> ${{ number_format($donation->donation_amount, 2) }}</p>
                        <p><strong style="color:#0056b3">Payment Method:</strong> {{ ucfirst($donation->payment_method) }}</p>
                        <p><strong style="color:#0056b3">Date:</strong> {{ $donation->formatted_date }}</p>
                        @if($donation->message)
                            <p><strong style="color:#0056b3">Message:</strong> {{ $donation->message }}</p>
                        @else
                            <p><strong style="color:#0056b3">Message:</strong> <i>No message provided</i></p>
                        @endif
                        <hr>
                    </div>
                    @endforeach
                </div>
                <div class="text-end mt-3">
                    <strong class="text-success">Total Donations: {{ count($donations) }} / </strong>
                    <strong class="text-primary">
                    Total Amount: ${{ number_format($monthly_totals_map[$monthYear] ?? 0, 2) }}
                    </strong>
                </div>
                <hr>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>
