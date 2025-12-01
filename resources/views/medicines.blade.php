<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medicines</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.nav-bar')
    <main>
    @if(session('success'))
    <div class="alert alert-success">
    {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <a href="#top" class="scroll-button">up</a>
        <div class="user-tab">
            <a href="#antibiotics">Antibiotics</a>
            <a href="#vaccines">Vaccines</a>
            <a href="#pain">Pain Relievers</a>
            <a href="#vitamins">Vitamins</a>
            <a href="#dermatologicals">Dermatologicals</a>
            @foreach($others as $other)
            <a href="#{{$other->category_name}}">{{ucfirst($other->category_name)}}</a>
            @endforeach
            @if(auth()->user()->hasAnyRole(['employee']))
            <a href="{{ url('/add_new_medicine') }}">Add new medicine</a>
            <a href="{{ url('/add_new_category') }}">Add new category</a>
            @endif
        </div>
        <!-- Search Bar -->
        <div class="search-container">
            <form action="{{ url('/search_medicine') }}" method="GET">
                <input type="text" name="query" placeholder="Search medicines..." class="search-input" value="{{ request()->query('query') }}">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <!-- Antibiotics Section -->
        <section id="antibiotics" class="user-section">
            <h2>Antibiotics</h2>
            <div class="user-grid">
            @foreach($antibiotics as $antibiotic)
                <div class="user-card">
                    <img src="{{ $antibiotic->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $antibiotic->picture) }}" alt="">
                    <h3>{{ucfirst($antibiotic->name)}}</h3>
                    <p>Description :{{$antibiotic ->description}} </p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($antibiotic->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $antibiotic->expire_date }}</span>
                        @else
                            {{ $antibiotic->expire_date }}
                        @endif
                    </p>  
                    <p>Details :{{$antibiotic ->details}} </p>
                    <p>Price :{{$antibiotic ->price}} </p>
                    <p>Quantity in Stock :{{$antibiotic ->quantity_in_stock}} </p>
                    <p>Availability: {{ $antibiotic->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                    @if(auth()->user()->hasAnyRole(['employee']))
                    <a href="{{ url('/update_medicine/' . $antibiotic->medicine_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/remove_medicine/' . $antibiotic->medicine_id) }}" class="view-profile">Disactivate</a>
                    @endif
                </div>
              @endforeach
            </div>
        </section>

          
        <!-- Vaccines Section -->
        <section id="vaccines" class="user-section">
            <h2>Vaccines</h2>
            <div class="user-grid">
            @foreach($vaccines as $vaccine)
            <div class="user-card">
                <img src="{{ $vaccine->picture === 'images/medicine.png' 
                ? asset('images/medicine.png') 
                : asset('storage/' . $vaccine->picture) }}" alt="">
                <h3>{{ucfirst($vaccine->name)}}</h3>
                <p>Description :{{$vaccine ->description}} </p>
                <p>Expire Date: 
                    @if(\Carbon\Carbon::parse($vaccine->expire_date)->isPast())
                        <span style="color: red;font-weight:bolder">Expired since  {{ $vaccine->expire_date }}</span>
                    @else
                        {{ $vaccine->expire_date }}
                    @endif
                </p>
                <p>Details :{{$vaccine ->details}} </p>
                <p>Price :{{$vaccine ->price}} </p>
                <p>Quantity in Stock :{{$vaccine ->quantity_in_stock}} </p>
                <p>Availability: {{ $vaccine->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                @if(auth()->user()->hasAnyRole(['employee']))
                <a href="{{ url('/update_medicine/' . $vaccine->medicine_id) }}" class="view-profile">Update</a>
                <a href="{{ url('/remove_medicine/' . $vaccine->medicine_id) }}" class="view-profile">Disactivate</a>
            @endif
            </div>
        @endforeach    
        </div>
        </section>

        <!-- Pain Relievers Section -->
        <section id="pain" class="user-section">
            <h2>Pain Relievers</h2>
            <div class="user-grid">
                @foreach($pain_relievers as $pain_reliever)
                <div class="user-card">
                    <img src="{{ $pain_reliever->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $pain_reliever->picture) }}" alt="">
                    <h3>{{ucfirst($pain_reliever->name)}}</h3>
                    <p>Description :{{$pain_reliever ->description}} </p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($pain_reliever->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $pain_reliever->expire_date }}</span>
                        @else
                            {{ $pain_reliever->expire_date }}
                        @endif
                    </p>
                    <p>Details :{{$pain_reliever ->details}} </p>
                    <p>Price :{{$pain_reliever ->price}} </p>
                    <p>Quantity in Stock :{{$pain_reliever ->quantity_in_stock}} </p>
                    <p>Availability: {{ $pain_reliever->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                    @if(auth()->user()->hasAnyRole(['employee']))
                    <a href="{{ url('/update_medicine/' . $pain_reliever->medicine_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/remove_medicine/' . $pain_reliever->medicine_id) }}" class="view-profile">Disactivate</a>
                 @endif
                </div>
            @endforeach   
            </div>
        </section>
        <!-- Vitamins Section -->
        <section id="vitamins" class="user-section">
            <h2> Vitamins</h2>
            <div class="user-grid">
                @foreach($vitamins as $vitamin)
                <div class="user-card">
                    <img src="{{ $vitamin->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $vitamin->picture) }}" alt="">
                    <h3>{{ucfirst($vitamin->name)}}</h3>
                    <p>Description :{{$vitamin ->description}} </p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($vitamin->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $vitamin->expire_date }}</span>
                        @else
                            {{ $vitamin->expire_date }}
                        @endif
                    </p>
                    <p>Details :{{$vitamin ->details}} </p>
                    <p>Price :{{$vitamin ->price}} </p>
                    <p>Quantity in Stock :{{$vitamin ->quantity_in_stock}} </p>
                    <p>Availability: {{ $vitamin->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                    @if(auth()->user()->hasAnyRole(['employee']))
                    <a href="{{ url('/update_medicine/' . $vitamin->medicine_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/remove_medicine/' . $vitamin->medicine_id) }}" class="view-profile">Disactivate</a>
                 @endif
                </div>
            @endforeach   
            </div>
        </section>

           <!-- Dermatologicals Section -->
           <section id="dermatologicals" class="user-section">
            <h2> Dermatologicals</h2>
            <div class="user-grid">
                @foreach($dermatologicals as $dermatological)
                <div class="user-card">
                    <img src="{{ $dermatological->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $dermatological->picture) }}" alt="">
                    <h3>{{ucfirst($dermatological->name)}}</h3>
                    <p>Description :{{$dermatological ->description}} </p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($dermatological->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $dermatological->expire_date }}</span>
                        @else
                            {{ $dermatological->expire_date }}
                        @endif
                    </p>
                    <p>Details :{{$dermatological ->details}} </p>
                    <p>Price :{{$dermatological ->price}} </p>
                    <p>Quantity in Stock :{{$dermatological ->quantity_in_stock}} </p>
                    <p>Availability: {{ $dermatological->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                    @if(auth()->user()->hasAnyRole(['employee']))
                    <a href="{{ url('/update_medicine/' . $dermatological->medicine_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/remove_medicine/' . $dermatological->medicine_id) }}" class="view-profile">Disactivate</a>
                 @endif
                </div>
            @endforeach   
            </div>
        </section>
        @foreach($others as $other)
        <section id="{{$other->category_name}}" class="user-section">
            
            <h2>{{ucfirst($other->category_name)}}</h2>
            <div class="user-grid">
                <div class="user-card">
                    <img src="{{ $other->picture === 'images/medicine.png' 
                    ? asset('images/medicine.png') 
                    : asset('storage/' . $other->picture) }}" alt="">
                    <h3>{{ucfirst($other->name)}}</h3>
                    <p>Description :{{$other ->description}} </p>
                    <p>Expire Date: 
                        @if(\Carbon\Carbon::parse($other->expire_date)->isPast())
                            <span style="color: red;font-weight:bolder">Expired since  {{ $other->expire_date }}</span>
                        @else
                            {{ $other->expire_date }}
                        @endif
                    </p>
                    <p>Details :{{$other ->details}} </p>
                    <p>Price :{{$other ->price}} </p>
                    <p>Quantity in Stock :{{$other ->quantity_in_stock}} </p>
                    <p>Availability: {{ $other->is_available == 1 ? 'Available' : 'Not Available' }}</p>
                    @if(auth()->user()->hasAnyRole(['employee']))
                    <a href="{{ url('/update_medicine/' . $other->medicine_id) }}" class="view-profile">Update</a>
                    <a href="{{ url('/remove_medicine/' . $other->medicine_id) }}" class="view-profile">Disactivate</a>
                    @endif
                 </div> 
            </div>
            @endforeach  
        </section>
        <div class="center-link-wrapper">
            <a href="{{ url('/disactivated_medicines/') }}" class="view-profile disactivated-link">
                Do you want to see disactivated medicines?
            </a>
        </div>
    </main>
    
<!-- Footer -->
@include('partials.footer')
<script src="{{ asset('JS/animation.js') }}"></script>
</body>
</html>

