<!DOCTYPE html>
<html lang="{{ $language }}" dir="{{ $language == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $translations['title'] }} {{ $animal->animal_name }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
        }
        
        .container {
            max-width: 1200px;
            padding-top: 2rem;
        }
        
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .rtl {
            direction: rtl;
            text-align: right;
        }
        
        .loading-spinner { 
            display: none;
            margin-left: 8px;
        }
        
        .question-btn {
            margin: 0.3rem;
            white-space: normal;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .question-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .language-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .rtl .language-switcher {
            right: auto;
            left: 20px;
        }
        
        #responseArea {
            white-space: pre-wrap;
            background-color: white;
            border-radius: 0.5rem;
            min-height: 400px;
            padding: 1.5rem;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 0.3rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
        }
        
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            .col-md-5, .col-md-7 {
                width: 100%;
                max-width: 100%;
            }
            .language-switcher {
                position: static;
                margin-bottom: 1rem;
            }
        }
        
        @if($language == 'ar')
            body {
                font-family: 'Tahoma', Arial, sans-serif;
            }
        @endif
        
        /* Animation for response loading */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #responseArea p, #responseArea div {
            animation: fadeIn 0.3s ease forwards;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #2e59d9;
        }
        
        /* Form styling */
        textarea.form-control {
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #d1d3e2;
            transition: border-color 0.3s;
        }
        
        textarea.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* Animal info styling */
        .animal-info p {
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .animal-info p:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    </style>
</head>
<body class="{{ $language == 'ar' ? 'rtl' : '' }}">
    <div class="container py-4">
        <div class="language-switcher">
            <div class="btn-group">
                <a href="?language=en" class="btn btn-sm btn-outline-secondary {{ $language == 'en' ? 'active' : '' }}">English</a>
                <a href="?language=ar" class="btn btn-sm btn-outline-secondary {{ $language == 'ar' ? 'active' : '' }}">العربية</a>
                <a href="?language=fr" class="btn btn-sm btn-outline-secondary {{ $language == 'fr' ? 'active' : '' }}">Français</a>
            </div>
        </div>

        <a href="{{ url('/index') }}" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left me-2"></i>{{ $translations['back'] }}
        </a>

        <h2 class="mb-4">{{ $translations['title'] }} <span class="text-primary">{{ $animal->animal_name }}</span></h2>
        
        <div class="row">
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-paw me-2"></i>{{ $translations['animal_info'] }}
                    </div>
                    <div class="card-body animal-info">
                        <p><strong>{{ $translations['type'] }}:</strong> <span class="float-end">{{ $animal->type }}</span></p>
                        <p><strong>{{ $translations['breed'] }}:</strong> <span class="float-end">{{ $animal->breed }}</span></p>
                        <p><strong>{{ $translations['age'] }}:</strong> <span class="float-end">{{ $age }} {{ $translations['years'] }}</span></p>
                        <p><strong>{{ $translations['status'] }}:</strong> 
                            <span class="float-end badge 
                                @if($animal->status == 'available') bg-success
                                @elseif($animal->status == 'adopted') bg-info
                                @elseif($animal->status == 'under_medical_care') bg-warning text-dark
                                @else bg-secondary
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $animal->status)) }}
                            </span>
                        </p>
                        <p><strong>{{ $translations['weight'] }}:</strong> <span class="float-end">{{ $animal->weight }} {{ $translations['kg'] }}</span></p>
                        <p><strong>{{ $translations['gender'] }}:</strong> <span class="float-end">{{ $animal->gender }}</span></p>
                        <p><strong>{{ $translations['color'] }}:</strong> <span class="float-end">{{ $animal->color }}</span></p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-question-circle me-2"></i>{{ $translations['question'] }}
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-3">
                            <button class="btn btn-outline-primary question-btn" data-question="{{ $translations['food_question'] }}">
                                <i class="fas fa-utensils me-1"></i>{{ $translations['food_question'] }}
                            </button>
                            <button class="btn btn-outline-primary question-btn" data-question="{{ $translations['medicine_question'] }}">
                                <i class="fas fa-pills me-1"></i>{{ $translations['medicine_question'] }}
                            </button>
                            <button class="btn btn-outline-primary question-btn" data-question="{{ $translations['behavior_question'] }}">
                                <i class="fas fa-brain me-1"></i>{{ $translations['behavior_question'] }}
                            </button>
                            <button class="btn btn-outline-primary question-btn" data-question="{{ $translations['vaccine_question'] }}">
                                <i class="fas fa-syringe me-1"></i>{{ $translations['vaccine_question'] }}
                            </button>
                            <button class="btn btn-outline-primary question-btn" data-question="{{ $translations['health_question'] }}">
                                <i class="fas fa-heartbeat me-1"></i>{{ $translations['health_question'] }}
                            </button>
                        </div>
                        
                        <form id="consultationForm">
                            @csrf
                            <input type="hidden" name="animal_id" value="{{ $animal->animal_id }}">
                            <input type="hidden" name="language" value="{{ $language }}">
                            
                            <div class="mb-3">
                                <textarea class="form-control" name="question" rows="4" 
                                    placeholder="{{ $translations['placeholder'] }}" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                {{ $translations['submit'] }}
                                <span class="loading-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-comment-medical me-2"></i>{{ $translations['title'] }}
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div id="responseArea" class="flex-grow-1">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="text-center text-muted">
                                    <i class="fas fa-comment-dots fa-3x mb-3"></i>
                                    <p>{{ $translations['loading'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
<!-- زر النزول لتحت -->
<button onclick="scrollOneStepDown()" class="btn btn-secondary position-fixed" style="bottom: 100px; right: 20px;">
    ⬇️
</button>

<!-- زر الطلوع لفوق -->
<button onclick="scrollOneStepUp()" class="btn btn-secondary position-fixed" style="bottom: 40px; right: 20px;">
    ⬆️
</button>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#consultationForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const spinner = form.find('.loading-spinner');
                const responseArea = $('#responseArea');
                
                submitBtn.prop('disabled', true);
                spinner.show();
                responseArea.html(`
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">{{ $translations['loading'] }}</p>
                        </div>
                    </div>
                `);
                
                $.ajax({
                    url: "{{ route('ai_handle') }}",
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            responseArea.html(response.response);
                        } else {
                            responseArea.html(`
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>${response.error}</div>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = '{{ __("An error occurred while processing your request") }}';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (xhr.statusText) {
                            errorMsg = xhr.statusText;
                        }
                        responseArea.html(`
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>${errorMsg}</div>
                            </div>
                        `);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.hide();
                    }
                });
            });
            
            // Handle quick question buttons
            $('.question-btn').on('click', function() {
                const question = $(this).data('question');
                $('textarea[name="question"]').val(question).focus();
            });
        });
            function scrollOneStepUp() {
        window.scrollBy({ top: -100, behavior: 'smooth' }); // خطوة لفوق
    }

    function scrollOneStepDown() {
        window.scrollBy({ top: 100, behavior: 'smooth' }); // خطوة لتحت
    }
    </script>
</body>
</html>