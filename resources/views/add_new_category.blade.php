<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.nav-bar')

    <main>
        <a href="#top" class="scroll-button">^</a>
        <div class="form-container">
            <h2>Add New Category</h2>
            <form action="{{ url('/add_new_category') }}" method="POST" enctype="multipart/form-data" >
                @csrf

                <div class="form-group">
                    <label for="category_name">Name</label>
                    <input type="text" id="category_name" name="category_name" placeholder="Category Name" required>
                </div>
                @error('category_name')
                {{$message}}
                @enderror

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" placeholder="Category Description" required>
                </div>
                @error('description')
                {{$message}}
                @enderror
               
                <button type="submit" class="submit-btn">Add Category</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>

</body>
</html>


