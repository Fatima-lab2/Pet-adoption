<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Animals - PawNoble</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      padding-top: 60px; /* ارتفاع الـ btn-container */
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .container {
      padding: 15px;
      max-width: 100%;
      font-size: 13px; /* قللت حجم الخط */
      margin-top: 0;
    }

    .btn-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: white;
      padding: 10px 15px;
      border-bottom: 1px solid #eee;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      z-index: 9999;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
      font-size: 12px; /* أقل حجم خط للجدول */
    }

    th {
      position: sticky;
      top: 60px;
      background: white;
      z-index: 998;
      padding: 6px 8px; /* تقليل الحشوة */
      border-bottom: 1px solid #ccc;
      max-width: 120px; /* تقليل عرض الرأس */
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    td, th {
      max-width: 120px; /* تقليل عرض الخلايا */
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      padding: 6px 8px;
      border-bottom: 1px solid #eee;
    }

    td:nth-child(3) {
      max-width: 60px; /* تقليل عرض عمود الصورة */
      text-align: center;
      padding: 4px;
    }

    td:nth-child(3) img {
      width: 50px; /* تصغير حجم الصورة */
      height: auto;
      border-radius: 4px;
      object-fit: cover;
    }

    td:last-child {
      max-width: 80px;
      text-align: center;
      padding: 6px 8px;
    }

    td:nth-child(11) {
      font-weight: bold;
      color: #27ae60;
    }

    td:nth-child(10) {
      text-transform: capitalize;
    }

    .btn.btn-primary.position-fixed {
      bottom: 100px;
      right: 20px;
    }

    .btn.btn-secondary.position-fixed {
      bottom: 40px;
      right: 20px;
    }

    @media (max-width: 768px) {
      body {
        padding-top: 56px;
      }
      .btn-container {
        top: 0;
        padding: 8px 10px;
      }
      th {
        top: 56px;
      }
      .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      table {
        min-width: 600px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="btn-container">
      <a href="#adopted" class="btn">Adopted Animals</a>
      <a href="#available" class="btn">Available Animals</a>
      <a href="#under_medical_care" class="btn">Under Medical Care</a>
      <a href="#foster" class="btn">Foster Animals</a>
      <a href="{{ route('add_animal') }}" class="btn">Add Animal</a>
      <a href="index" class="btn btn-secondary mb-4">Back To Home</a>
    </div>

    <!-- Adopted Animals Section -->
    <h3 id="adopted">Adopted Animals</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Image</th>
          <th>Type</th>
          <th>Gender</th>
          <th>Breed</th>
          <th>Birth date</th>
          <th>Color</th>
          <th>Weight</th>
          <th>Status</th>
          <th>Price</th>
          <th>Arrival Date</th>
          <th>Room</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($groupedAnimals['adopted'] as $animal)
        <tr>
          <td>{{ $animal->animal_id }}</td>
          <td>{{ $animal->animal_name }}</td>
          <td>
            @if($animal->image)
                <img src="{{ $animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $animal->image) }}" alt="Animal">
            @else
            No image
            @endif
          </td>
          <td>{{ $animal->type }}</td>
          <td>{{ $animal->gender }}</td>
          <td>{{ $animal->breed }}</td>
          <td>{{ $animal->birth_date }}</td>
          <td>{{ $animal->color }}</td>
          <td>{{ $animal->weight }} kg</td>
          <td>{{ ucfirst(str_replace('_', ' ', $animal->status)) }}</td>
          <td>${{ $animal->price }}</td>
          <td>{{ $animal->arrival_date }}</td>
          <td>{{ $animal->room_name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('edit_animal', $animal->animal_id) }}" class="btn btn-primary btn-sm">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="back-to-top">
      <button onclick="scrollToTop()" class="btn btn-sm">Back to Top</button>
    </div>

    <!-- Available Animals Section -->
    <h3 id="available">Available Animals</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Image</th>
          <th>Type</th>
          <th>Gender</th>
          <th>Breed</th>
          <th>Birth Date</th>
          <th>Color</th>
          <th>Weight</th>
          <th>Status</th>
          <th>Price</th>
          <th>Arrival Date</th>
          <th>Room</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($groupedAnimals['available'] as $animal)
        <tr>
          <td>{{ $animal->animal_id }}</td>
          <td>{{ $animal->animal_name }}</td>
          <td>
            @if($animal->image)
              <img src="{{ $animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $animal->image) }}" alt="Animal">

            @else
            No image
            @endif
          </td>
          <td>{{ $animal->type }}</td>
          <td>{{ $animal->gender }}</td>
          <td>{{ $animal->breed }}</td>
          <td>{{ $animal->birth_date }}</td>
          <td>{{ $animal->color }}</td>
          <td>{{ $animal->weight }} kg</td>
          <td>{{ ucfirst(str_replace('_', ' ', $animal->status)) }}</td>
          <td>${{ $animal->price }}</td>
          <td>{{ $animal->arrival_date }}</td>
          <td>{{ $animal->room_name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('edit_animal', $animal->animal_id) }}" class="btn btn-primary btn-sm">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="back-to-top">
      <button onclick="scrollToTop()" class="btn btn-sm">Back to Top</button>
    </div>

    <!-- Under Medical Care Animals Section -->
    <h3 id="under_medical_care">Under Medical Animals</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Image</th>
          <th>Type</th>
          <th>Gender</th>
          <th>Breed</th>
          <th>Birth date</th>
          <th>Color</th>
          <th>Weight</th>
          <th>Status</th>
          <th>Price</th>
          <th>Arrival Date</th>
          <th>Room</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($groupedAnimals['under_medical_care'] as $animal)
        <tr>
          <td>{{ $animal->animal_id }}</td>
          <td>{{ $animal->animal_name }}</td>
          <td>
            @if($animal->image)
                 <img src="{{ $animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $animal->image) }}" alt="Animal">
            @else
            No image
            @endif
          </td>
          <td>{{ $animal->type }}</td>
          <td>{{ $animal->gender }}</td>
          <td>{{ $animal->breed }}</td>
          <td>{{ $animal->birth_date }}</td>
          <td>{{ $animal->color }}</td>
          <td>{{ $animal->weight }} kg</td>
          <td>{{ ucfirst(str_replace('_', ' ', $animal->status)) }}</td>
          <td>${{ $animal->price }}</td>
          <td>{{ $animal->arrival_date }}</td>
          <td>{{ $animal->room_name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('edit_animal', $animal->animal_id) }}" class="btn btn-primary btn-sm">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="back-to-top">
      <button onclick="scrollToTop()" class="btn btn-sm">Back to Top</button>
    </div>

    <!-- Foster Animals Section -->
    <h3 id="foster">Foster Animals</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Image</th>
          <th>Type</th>
          <th>Gender</th>
          <th>Breed</th>
          <th>Birth date</th>
          <th>Color</th>
          <th>Weight</th>
          <th>Status</th>
          <th>Price</th>
          <th>Arrival Date</th>
          <th>Room</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($groupedAnimals['foster'] as $animal)
        <tr>
          <td>{{ $animal->animal_id }}</td>
          <td>{{ $animal->animal_name }}</td>
          <td>
            @if($animal->image)
              <img src="{{ $animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $animal->image) }}" alt="Animal">

            @else
            No image
            @endif
          </td>
          <td>{{ $animal->type }}</td>
          <td>{{ $animal->gender }}</td>
          <td>{{ $animal->breed }}</td>
          <td>{{ $animal->birth_date }}</td>
          <td>{{ $animal->color }}</td>
          <td>{{ $animal->weight }} kg</td>
          <td>{{ ucfirst(str_replace('_', ' ', $animal->status)) }}</td>
          <td>${{ $animal->price }}</td>
          <td>{{ $animal->arrival_date }}</td>
          <td>{{ $animal->room_name ?? 'N/A' }}</td>
          <td>
            <a href="{{ route('edit_animal', $animal->animal_id) }}" class="btn btn-primary btn-sm">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="back-to-top">
      <button onclick="scrollToTop()" class="btn btn-sm">Back to Top</button>
    </div>
  </div>
  
 <!-- زر النزول لتحت -->
<button onclick="scrollOneStepDown()" class="btn btn-primary position-fixed" style="bottom: 100px; right: 20px;">
    ⬇️
</button>

<!-- زر الطلوع لفوق -->
<button onclick="scrollOneStepUp()" class="btn btn-secondary position-fixed" style="bottom: 40px; right: 20px;">
    ⬆️
</button>
  <script>
    function scrollToTop() {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
     function scrollOneStepUp() {
        window.scrollBy({ top: -100, behavior: 'smooth' }); // خطوة لفوق
    }

    function scrollOneStepDown() {
        window.scrollBy({ top: 100, behavior: 'smooth' }); // خطوة لتحت
    }
  </script>
</body>
</html>
