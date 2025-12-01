<!DOCTYPE html>
<html>
<head>
    <title>Animal Feeding Schedule</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <style>
        .animal-container {
            margin-bottom: 30px;
        }
        .animal-card {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .animal-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 20px;
        }
        .schedule-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .schedule-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .food-item {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .food-name {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .food-detail {
            margin-bottom: 5px;
        }
        .food-detail-label {
            font-weight: bold;
        }
        .expiry-warning {
            color: #dc3545;
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
    </style>
</head>
<body class="bg-light">
    
    @if(auth()->check())
        <nav style="background-color: #f8f9fa; padding: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Welcome, {{ auth()->user()->name }}</span>
                
                </form>
            </div>
        </nav>
    @endif

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Animal Feeding Schedules</h2>
            @if(isset($animal_id))
                <a href="{{ url('room') }}" class="btn btn-secondary">Back to Rooms</a>
            @endif
        </div>

        @if(empty($animals))
            <div class="alert alert-info">No feeding schedules found.</div>
        @endif

        @foreach($animals as $animal_id => $animal)
        <div class="animal-container">
            <div class="animal-card">
                <img src="{{ $animal['image'] === 'images/animal.jpg' 
    ? asset('images/animal.jpg') 
    : asset('storage/' . $animal['image']) }}" 
    alt="{{ $animal['animal_name'] }}" 
    class="animal-img">


                <h3 class="mb-0">{{ $animal['animal_name'] }}</h3>
            </div>
            
            @foreach($animal['schedules'] as $schedule_id => $schedule)
            <div class="schedule-card">
                <div class="schedule-header">
                    <div>
                        <span class="schedule-method">{{ $schedule['method'] }}</span>
                        <span class="schedule-frequency">{{ $schedule['frequency'] }}</span>
                    </div>
                </div>
                
                <div class="row g-3">
                    @foreach($schedule['foods'] as $food)
                    <div class="col-md-6">
                        <div class="food-item">
                            <div class="food-name">{{ $food['food_name'] }}</div>
                            
                            <div class="food-detail">
                                <span class="food-detail-label">Type:</span> 
                                {{ $food['food_type'] }}
                            </div>
                            
                            <div class="food-detail">
                                <span class="food-detail-label">Description:</span> 
                                {{ $food['food_description'] }}
                            </div>
                            
                            <div class="food-detail">
                                <span class="food-detail-label">Quantity:</span> 
                                {{ $food['food_quantity'] }} units
                            </div>
                            
                            <div class="food-detail">
                                <span class="food-detail-label">Expires:</span> 
                                @php 
                                    $expiry_date = new DateTime($food['food_expire_date']);
                                    $today = new DateTime();
                                    $interval = $today->diff($expiry_date);
                                    
                                    if ($expiry_date < $today) {
                                        echo '<span class="expiry-warning">Expired on ' . $expiry_date->format('Y-m-d') . '</span>';
                                    } elseif ($interval->days <= 30) {
                                        echo '<span class="expiry-warning">Expires soon (' . $expiry_date->format('Y-m-d') . ')</span>';
                                    } else {
                                        echo $expiry_date->format('Y-m-d');
                                    }
                                @endphp
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

        <button onclick="topFunction()" id="goTopBtn" title="Go to top">↑</button>
    </div>

    <script>
        let topBtn = document.getElementById("goTopBtn");
        window.onscroll = function () {
            topBtn.style.display = (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) ? "block" : "none";
        };

        function topFunction() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>