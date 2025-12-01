<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Adopt an Animal</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
@include('partials.nav-bar')
<body>
  <div class="form-container">
    <h2>Adoption Request Form</h2>

    <form action="{{ url('/adopte_animal/'.$available_animal->animal_id) }}" method="POST">
        @csrf
      <input type="hidden" name="animal_id" value="{{$available_animal->animal_id}}">
      <div class="form-group">
        <input type="hidden" value="{{$available_animal->animal_name}}" readonly>
      </div>

      <div class="form-group">
        <label>Your Full Name:</label>
        <input type="text" name="user_name" value="{{ucfirst( Auth::user()->name) }}" readonly>
      </div>

      <!-- Adoption Details -->
      <!--Reason-->
      <div class="form-group">
        <label>Why do you want to adopt this animal?</label>
        <textarea name="reason" required></textarea>
      </div>
         <!--other_pets-->
      <div class="form-group">
        <label>Do you have other pets?</label>
        <select name="other_pets" required>
          <option value="" disabled selected>Select an option</option>
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>
        <!--has_Children-->
      <div class="form-group">
        <label>Do you have children?</label>
        <select name="has_children" required>
          <option value="" disabled selected>Select an option</option>
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>
      <!--home_type-->
      <div class="form-group">
        <label>Type of Home:</label>
        <select name="home_type" required>
          <option value="" disabled selected>Select a home type</option>
          <option value="apartment">Apartment</option>
          <option value="house">House</option>
          <option value="farm">Farm</option>
        </select>
      </div>
      <!--Experience-->
      <div class="form-group">
        <label>Do you have experience with pets?</label>
        <textarea name="experience" placeholder="Describe your past experience (if any)"></textarea>
      </div>

      <button type="submit" class="submit-btn">Submit Adoption Request</button>
    </form>
  </div>
  <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
