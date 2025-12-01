<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disactivated Medicines </title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <section id="doctors" class="user-section">
            <h2>Disactivated Medicines</h2>
            <div class="user-grid">
                @foreach($disactivated_medicines as $disactivated_medicine)
                <div class="user-card">
                    <img src="{{ $disactivated_medicine->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $disactivated_medicine->picture) }}" alt="Doctor">
                    <h3>{{ucfirst($disactivated_medicine->name)}}</h3>
                    <p>Description:{{ $disactivated_medicine->description }}</p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($disactivated_medicine->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $disactivated_medicine->expire_date }}</span>
                        @else
                            {{ $disactivated_medicine->expire_date }}
                        @endif
                    </p>  
                    <p>Details: {{$disactivated_medicine->details}}</p>
                    <p>Price: {{$disactivated_medicine->price}}</p>
                    <p>Quantity in Stock :{{$disactivated_medicine ->quantity_in_stock}} </p>
                    <form action="{{ url('/disactivated_medicines/'.$disactivated_medicine->medicine_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="medicine_id" value="{{ $disactivated_medicine->medicine_id }}">
                        <label for="is_available">Availability:</label>
                        <select name="is_available">
                            <option value="0" selected>Unavailable</option>
                        </select>
                        <button type="submit" class="view-profile">Available</button>
                    </form>
                </div>
                @endforeach
            </div>
        </section>
    </main>
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>