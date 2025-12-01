
<!DOCTYPE html>
<html>
<head>
    <title>Reject Adoption Request</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
@include('partials.nav-bar')
    <h2 style="text-align: center">Reject Adoption Request</h2>

   <form class="styled-form" action="{{url('/reject_adoption_request/'.$request_id)}}" method="POST">
    @csrf
  <h2>Approve Adoption</h2>
  
  <label for="notes"> Rejection Reason</label>
  <textarea id="rejection_reason" name="rejection_reason"></textarea>

  <div class="button-group">
    <button type="submit">Submit</button>
    <a href="/adoptions" class="btn cancel-btn">Cancel</a>
  </div>
</form>

</body>
</html>
