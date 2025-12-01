<!DOCTYPE html>
<html>
<head>
    <title>Adoption Request Status</title>
</head>
<body>
    <h1>Your Adoption Request Status</h1>
    <p>We wanted to let you know that your adoption request has been <strong>{{ $status }}</strong>.</p>
    @if($status == 'rejected')
        <p><strong>Reason:</strong> {{ $reason }}</p>
    @endif
    <p>Thank you for being a part of our adoption process.</p>
</body>
</html>
