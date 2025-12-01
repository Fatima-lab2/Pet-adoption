
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Animals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&family=Work+Sans:wght@100..900&display=swap" rel="stylesheet">
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
@include('partials.nav-bar')
<body>
    @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

    <h2 style="text-align: center">
        @auth
        Welcome {{ ucfirst(auth()->user()->name) }}
        @endauth
    </h2>
    <a href="#top" class="scroll-button" style="text-decoration: none">up</a>
    <div class="user-tab">
            <a href="#available">Available Animals</a>
            <a href="#sick">Sick Animals</a>
            @auth
            @if(auth()->user()->hasAnyRole(['employee', 'admin','doctor']))
            <a href="#adopted">Adopted Animals</a>
           
            @endif
            @endauth
        </div> 
           <!-- Add this search form -->
    <div class="search-box">
    <form action="{{ url('/index') }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search animals..." 
               value="{{ request('search') }}" class="search-input">
        <button type="submit" class="btn-search-small">
            Search
        </button>
        @if(request('search'))
            <a href="{{ url('/index') }}" class="btn-clear-small">
                Clear
            </a>
        @endif
    </form>
</div>
    <main>

    <h2>Available Animals</h2>
        <section class="animal-list" id="available">
            @foreach($available_animals as $available_animal)
            <div class="animal-card img">
                <img src="{{ $available_animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $available_animal->image) }}" alt="Animal">
                <ul>
                    <li><strong style="color:#007bff">Name:</strong> {{$available_animal->animal_name}}</li>
                    <li><strong style="color:#007bff">Breed:</strong> {{$available_animal->breed}}</li>
                    <li><strong style="color:#007bff">Gender:</strong> {{$available_animal->gender}}</li>
                    <li><strong style="color:#007bff">Date of Birth:</strong> {{$available_animal->birth_date}}</li>
                    <li><strong style="color:#007bff">Weight:</strong> {{$available_animal->weight}}</li>
                    <li><strong style="color:#007bff">Status:</strong> {{$available_animal->status}}</li>
                    <li><strong style="color:#007bff">Arrival date:</strong> {{$available_animal->arrival_date}}</li>
                    <li><strong style="color:#007bff">Price:</strong> {{$available_animal->price}}</li>
                    <li><strong style="color:#007bff">Room:</strong> {{$available_animal->room_name ?? 'No Room Assigned'}}</li>
                </ul>
                <a href="{{url('/adopte_animal/'.$available_animal->animal_id)}}" class="cancel-btn">Adopte</a>
                 @auth
                 <a href="{{url('/ai_consultation/'.$available_animal->animal_id)}}" class="cancel-btn">Ask AI</a>
                 @endauth
               @auth
                  @if(auth()->user()->hasAnyRole(['doctor']))
              <a href="{{url('/health_record/'.$available_animal->animal_id)}}" class="cancel-btn">Health record</a>
              @endif
               @endauth
            </div>
            @endforeach
        </section>
        @auth
        @if(auth()->user()->hasAnyRole(['employee', 'admin','doctor']))
        <h2>Adopted Animals</h2>
        <section class="animal-list" id="adopted">
            @foreach($adopted_animals as $adopted_animal)
            <div class="animal-card img">
                <img src="{{ $adopted_animal->image === 'images/animal.jpg' 
                    ? asset('images/animal.jpg') 
                    : asset('storage/' . $adopted_animal->image) }}" alt="Animal">
                <ul>
                    <li><strong style="color:#007bff">Name:</strong> {{$adopted_animal->animal_name}}</li>
                    <li><strong style="color:#007bff">Breed:</strong> {{$adopted_animal->breed}}</li>
                    <li><strong style="color:#007bff">Gender:</strong> {{$adopted_animal->gender}}</li>
                    <li><strong style="color:#007bff">Date of Birth:</strong> {{$adopted_animal->birth_date}}</li>
                    <li><strong style="color:#007bff">Weight:</strong> {{$adopted_animal->weight}}</li>
                    <li><strong style="color:#007bff">Status:</strong> {{$adopted_animal->status}}</li>
                    <li><strong style="color:#007bff">Arrival date:</strong> {{$adopted_animal->arrival_date}}</li>
                    <li><strong style="color:#007bff">Price:</strong> {{$adopted_animal->price}}</li>
                    <li><strong style="color:#007bff">Room:</strong> {{$adopted_animal->room_name}}</li>
               
                </ul>
                 @auth
                 <a href="{{url('/ai_consultation/'.$adopted_animal->animal_id)}}" class="cancel-btn">Ask AI</a>
                 @endauth
                  @auth
                    @if(auth()->user()->hasAnyRole(['doctor']))
                 <a href="{{url('/health_record/'.$adopted_animal->animal_id)}}" class="cancel-btn">Health record</a>
                 @endif
                  @endauth
            </div>
            @endforeach
            </section>
           @endif
            @endauth
            <!--Sick Animals-->
   
        
        <h2>Unready animals' for adoption </h2>
        <section class="animal-list" id="sick">
        @foreach($sick_animals as $sick_animal)
            <div class="animal-card img">
                <img src="{{ $sick_animal->image === 'images/animal.jpg' 
                ? asset('images/animal.jpg') 
                : asset('storage/' . $sick_animal->image) }}" alt="Animal">
                <ul>
                    <li><strong style="color:#007bff;text-decoration:dashed">Name:</strong> {{$sick_animal->animal_name}}</li>
                    <li><strong style="color:#007bff">Breed:</strong> {{$sick_animal->breed}}</li>
                    <li><strong style="color:#007bff">Gender:</strong> {{$sick_animal->gender}}</li>
                    <li><strong style="color:#007bff">Date of Birth:</strong> {{$sick_animal->birth_date}}</li>
                    <li><strong style="color:#007bff">Weight:</strong> {{$sick_animal->weight}}</li>
                    <li><strong style="color:#007bff">Status:</strong> {{$sick_animal->status}}</li>
                    <li><strong style="color:#007bff">Arrival date:</strong> {{$sick_animal->arrival_date}}</li>
                    <li><strong style="color:#007bff">Price:</strong> {{$sick_animal->price}}</li>
                    <li><strong style="color:#007bff">Room:</strong> {{$sick_animal->room_name}}</li>
                </ul>
                 @auth
                 <a href="{{url('/ai_consultation/'.$sick_animal->animal_id)}}" class="cancel-btn">Ask AI</a>
                 @endauth
                  @auth
                    @if(auth()->user()->hasAnyRole(['doctor']))
                    <a href="{{url('/health_record/'.$sick_animal->animal_id)}}" class="cancel-btn">Health record</a>
                 @endif
                  @endauth
            </div>
            @endforeach
        </section>
 
          
    </main>
    @include('partials.footer')
    <script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>