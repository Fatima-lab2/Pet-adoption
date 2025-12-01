<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Medicine</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <main>
        <a href="#top" class="scroll-button">^</a>
        <div class="form-container">
            <h2>Add New Medicine</h2>
            <form action="{{ url('/add_new_medicine') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="form-group">
                    <label for="picture">Medicine Image</label>
                    <input type="file" id="picture" name="picture" accept="image/*" >
                </div>
                @error('picture')
                {{ $message }}
                @enderror

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                @error('name')
                {{$message}}
                @enderror

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" required>
                </div>
                @error('description')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="details">Details</label>
                    <input type="text" id="details" name="details" required>
                </div>
                @error('details')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="quantity_in_stock">Quantity </label>
                    <input type="number" id="quantity_in_stock" name="quantity_in_stock" required>
                </div>
                @error('quantity_in_stock')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="price">Price($) </label>
                    <input type="number" id="price" name="price" required>
                </div>
                @error('price')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="expire_date">Expire Date: </label>
                    <input type="date" id="expire_date" name="expire_date" required>
                </div>
                @error('expire_date')
                {{$message}}
                @enderror
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $categorie)
                        <option value="{{ $categorie->category_id }}">{{ ucfirst($categorie->category_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="submit-btn">Add Medicine</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>

