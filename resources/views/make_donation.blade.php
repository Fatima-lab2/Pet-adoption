<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Donation</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
   
</head>
<body>

@include('partials.nav-bar')

<div class="donation-container">
    <h3> Help us care for animals <br> Your donation makes a difference! 💙</h3>
    <form action="{{ url('/make_donation') }}" method="POST">
        @csrf

        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="">-- Choose payment method --</option>
            <option value="OMT">OMT</option>
            <option value="Whish Money">Whish Money</option>
        </select>
        @error('payment_method') 
        <p style="color:red">{{ $message }}</p>
        @enderror
        <label for="donation_amount">Donation Amount ($):</label>
        <input type="number" name="donation_amount" id="donation_amount" required >
        @error('donation_amount') 
        <p style="color:red">{{ $message }}</p>
        @enderror
        <label for="transaction_reference">Transaction Reference(last 4 numbers only) :</label>
        <input type="password" name="transaction_reference" id="transaction_reference" required>

        <p class="payment-note">
            * For credit cards, only provide the last 4 digits.<br>
            * No sensitive data like CVV or card password is stored.
        </p>
        <div class="form-group">
            <label for="message">Message (optional):</label>
            <textarea name="message" id="message" rows="4" placeholder="Write a message..."></textarea>
        </div>

        <div class="form-submit">
            <button type="submit" class="donate-button">Donate</button>
        </div>    </form>
</div>

</body>
</html>
