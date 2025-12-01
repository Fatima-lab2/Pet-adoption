<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Medicine</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
        <div class="update-container">
            <h2>Update Medicine</h2>

            <form action="{{ url('/update_medicine/' . $medicine->medicine_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="picture">Current Image:</label><br>
                <img src="{{ $medicine->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $medicine->picture) }}" alt="Medicine Image" style="max-width: 150px;"><br><br>

                <label for="picture">Upload New Image (optional):</label>
                <input type="file" id="picture" name="picture">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{$medicine->name}}" placeholder="Update medicine name" required>
                @error('name')
                {{$message}}
                @enderror
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="{{$medicine->description}}" placeholder="Update email" required>
                @error('description')
                {{$message}}
                @enderror
                <label for="details">Details:</label>
                <input type="text" id="details" name="details"value="{{$medicine->details}}"  placeholder="Update the details" required>
                @error('details')
                {{$message}}
                @enderror
                <label for="quantity_in_stock">Quantity:</label>
                <input type="text" id="quantity_in_stock" name="quantity_in_stock" value="{{$medicine->quantity_in_stock}}" placeholder="Update the quantity" required>
                @error('quantity_in_stock')
                {{$message}}
                @enderror

                <label for="expire_date">Expire Date:</label>
                <input type="date" id="expire_date" name="expire_date" value="{{$medicine->expire_date}}" placeholder="Update the expire_date" required>
                @error('expire_date')
                {{$message}}
                @enderror
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="{{$medicine->price}}" placeholder="Update the price" required>
                @error('price')
                {{$message}}
                @enderror
                
                <label for="role">Category:</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach ($categories as $categorie)
                    <option value="{{ $categorie->category_id }}" 
                        {{ $categorie->category_id == $medicine->category_id ? 'selected' : '' }}>
                        {{ $categorie->category_id }} - {{ ucfirst($categorie->category_name) }}
                    </option>
                    
                    @endforeach
                </select>
                
                <label for="is_active">Status:</label>
                <select name="is_available">
                    <option value="1" {{ $medicine->is_available ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ !$medicine->is_available ? 'selected' : '' }}>Unavailable</option>
                </select>
                
          <button type="submit">Update Medicine</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>
