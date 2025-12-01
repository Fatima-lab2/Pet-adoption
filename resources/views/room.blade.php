<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rooms and Animals</title>
   <link rel="icon" href="{{ asset('images/logo.png') }}">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
        .room-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            background-color: #f9f9f9;
        }
        .animal-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .animal-card {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px;
            width: 291px;
            background-color: white;
        }
        .animal-image {
            width: 50%;
            height: 170px;
            object-fit: cover;
            border-radius: 4px;
        }
        .room-status {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .room-available {
            background-color: #d4edda;
            color: #155724;
        }
        .room-unavailable {
            background-color: #f8d7da;
            color: #721c24;
        }
        .room-full {
            background-color: #fff3cd;
            color: #856404;
        }
        button {
            margin: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .editAnimalBtn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        #goTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: #555;
            color: white;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
        }
        #goTopBtn:hover {
            background-color: #333;
        }
        .alert {
            margin: 15px 0;
        }
    </style>
</head>
<body>
    

    @if(auth()->check())
        <nav style="background-color: #f8f9fa; padding: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Welcome, {{ auth()->user()->name }}</span>
            </div>
        </nav>
    @endif

    @if(session('success'))
        <div class="alert alert-success container">
            @if(session('success') == 'animal_updated')
                Animal information updated successfully!
            @elseif(session('success') == 'room_updated')
                Room information updated successfully!
            @else
                {{ session('success') }}
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger container">
            @if(session('error') == 'invalid_id')
                Invalid ID provided
            @elseif(session('error') == 'animal_not_found')
                Animal not found
            @elseif(session('error') == 'room_not_found')
                Room not found
            @else
                {{ session('error') }}
            @endif
        </div>
    @endif

    <section class="room-section container py-4">
        <h2>Rooms and Animals</h2>

        <!-- إضافة خانة البحث -->
        <div class="mb-3">
            <input type="text" id="searchRoomInput" class="form-control" placeholder="Search by room name..." onkeyup="filterRooms()">
        </div>

        @if(count($rooms) > 0)
            @foreach($rooms as $room)
                @php
                    $is_available = $room->availability == 1;
                    $is_full = $room->animal_count >= $room->capacity;

                    $status_class = '';
                    $status_text = '';
                    if (!$is_available) {
                        $status_class = 'room-unavailable';
                        $status_text = 'Not Available';
                    } elseif ($is_full) {
                        $status_class = 'room-full';
                        $status_text = 'Full';
                    } else {
                        $status_class = 'room-available';
                        $status_text = 'Available';
                    }
                @endphp

                <div class='room-card' data-room-name="{{ strtolower($room->name) }}">
                    <h3>Room: {{ $room->name }} <span class='room-status {{ $status_class }}'>{{ $status_text }}</span></h3>
                    <a href="/index" class="btn btn-secondary">Back</a>
                    <p><strong>Capacity:</strong> {{ $room->capacity }} animals (Current: {{ $room->animal_count }})</p>
                    <p><strong>Category:</strong> {{ $room->category }}</p>
                    <p><strong>Description:</strong> {{ $room->description }}</p>

                     @auth
                    @if(auth()->user()->hasAnyRole(['employee']))
                        <a href='{{ route("edit_room", $room->room_id) }}'><button class='editRoomBtn'>Edit Room</button></a>
                     @endif
                @endauth
                    <div class='animal-list'>
                        @if(isset($animalsByRoom[$room->room_id]) && count($animalsByRoom[$room->room_id]) > 0)
                            @foreach($animalsByRoom[$room->room_id] as $animal)
                                <div class='animal-card'>
                                    <img src="{{ $animal->image === 'images/animal.jpg' 
                                                  ? asset('images/animal.jpg') 
                                                       : asset('storage/' . $animal->image) }}" 
                                                           alt="{{ $animal->animal_name }}" 
                                                           class="animal-image">
                                    <h4>{{ $animal->animal_name }}</h4>
                                    <p><strong>Type:</strong> {{ $animal->type }}</p>
                                    <p><strong>Breed:</strong> {{ $animal->breed }}</p>
                                    <p><strong>Age:</strong> {{ $animal->birth_date }}</p>
                                    <p><strong>Gender:</strong> {{ $animal->gender }}</p>
                                    <p><strong>Color:</strong> {{ $animal->color }}</p>
                                    <p><strong>Weight:</strong> {{ $animal->weight }} kg</p>
                                    
                                    @php
                                        $status_text = '';
                                        switch ($animal->status) {
                                            case 'available': $status_text = 'Available'; break;
                                            case 'adopted': $status_text = 'Adopted'; break;
                                            case 'under_medical_care': $status_text = 'Under Medical Care'; break;
                                            case 'foster': $status_text = 'Foster'; break;
                                            default: $status_text = 'Unknown';
                                        }
                                    @endphp
                                    <p><strong>Status:</strong> {{ $status_text }}</p>

                                     @auth
                        @if(auth()->user()->hasAnyRole(['employee']))
                                        @php
                                            $room_available = DB::selectOne("SELECT availability FROM room WHERE room_id = ?", [$animal->room_id]);
                                            $is_room_available = $room_available && $room_available->availability == 1;
                                        @endphp
                                        @if($is_room_available)
                                            <a href='{{ route("edit_animal", $animal->animal_id) }}'><button class='editAnimalBtn'>Edit Animal</button></a>
                                        @else
                                            <button class='editAnimalBtn' disabled title='Room not available'>Edit Animal</button>
                                        @endif
                             
                <a href='{{ route("animal_food", ["animal_id" => $animal->animal_id]) }}'><button class='viewFeedingBtn'>Manage Feeding</button></a>
                                        @endif
                @endauth
                
                                     @auth
                    @if(auth()->user()->hasAnyRole(['volunteer']))
                                    <a href='{{ url("food_volunteer?animal_id=$animal->animal_id") }}'><button class='viewFeedingBtn'>View Feeding</button></a>
                                     @endif
                @endauth
                                </div>
                            @endforeach
                        @else
                            <p>No animals in this room.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p>No rooms available.</p>
        @endif
    </section>

     <!-- زر النزول لتحت -->
<button onclick="scrollOneStepDown()" class="btn btn-primary position-fixed" style="bottom: 100px; right: 20px;">
    ⬇️
</button>

<!-- زر الطلوع لفوق -->
<button onclick="scrollOneStepUp()" class="btn btn-secondary position-fixed" style="bottom: 40px; right: 20px;">
    ⬆️
</button>

    <script>
        window.onscroll = function() { toggleGoTopButton(); };

        function toggleGoTopButton() {
            const btn = document.getElementById("goTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }
        function scrollOneStepUp() {
            window.scrollBy({ top: -100, behavior: 'smooth' }); // خطوة لفوق
        }

        function scrollOneStepDown() {
            window.scrollBy({ top: 100, behavior: 'smooth' }); // خطوة لتحت
        }

        // دالة فلترة الغرف حسب الاسم
        function filterRooms() {
            const input = document.getElementById('searchRoomInput');
            const filter = input.value.toLowerCase();
            const rooms = document.querySelectorAll('.room-card');

            rooms.forEach(room => {
                const roomName = room.getAttribute('data-room-name');
                if(roomName.includes(filter)) {
                    room.style.display = '';
                } else {
                    room.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
