<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopta Nova</title>
     <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&family=Work+Sans:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<!-- Navbar -->
   
<body>
 @include('partials.nav-bar')
<a href="#top" class="scroll-button">up</a>
    <!-- Part 1 -->
     <section class="main-section">

            <div class="leftRight-part">
                <div class="leftPart ">
                    <div class="leftPartItems">
                    <h1>We Don't Just Rescue Animals; We Create Second Chances</h1>
                    <p>Providing shelter, medical care, and loving homes to abandoned and homeless animals. Join us in making a difference.</p>
                    <a href="{{url('/index')}}">GET STARTED</a>
                </div>
            </div>
                <div class="rightPart">
                    <img src="images\welcome_image.jpg" class="image">
                </div>
          
        </div>
     </section>
     <!-- Part 2 -->
     <section class="part2">
  <div class="container">
    <div class="cardsDIV">
      <div class="card" >
        <i class="bi bi-truck-front">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi bi-truck-front">
            <path d="M1 2a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v3h1.293a1 1 0 0 1 .707.293l1.207 1.207A1 1 0 0 1 16 6.707V13a1 1 0 0 1-1 1h-1v1a1 1 0 0 1-2 0v-1H4v1a1 1 0 0 1-2 0v-1H1a1 1 0 0 1-1-1V2z" fill="#1E90FF"/>
          </svg>
        </i>
        <h3>Animal Rescue</h3>
        <p>We rescue abandoned, abused, and homeless animals, offering them a safe haven and a second chance at life.</p>
      </div>

      <div class="card">
        <i class="bi bi-hospital">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi bi-hospital">
            <path d="M0 0v16h16V0H0zm14 14H2V2h12v12zM7 7H5V5h2V3h2v2h2v2H9v2H7V7z" fill="#0099e5"/>
          </svg>
        </i>
        <h3>Medical Care</h3>
        <p>Our in-shelter vet ensures that every animal receives timely vaccinations, treatments, and health monitoring.</p>
      </div>

      <div class="card">
        <i class="bi bi-house-heart-fill">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi bi-house-heart-fill">
            <path d="M8 3.293l6 6V15a1 1 0 0 1-1 1h-4v-4H7v4H3a1 1 0 0 1-1-1V9.293l6-6zm0-1.586l-7 7V15a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8.707l-7-7z" fill="#1E90FF"/>
          </svg>
        </i>
        <h3>Adoption Services</h3>
        <p>Help an animal find their forever home. We facilitate smooth, loving adoptions for families and individuals.</p>
      </div>

      <div class="card">
        <i class="bi bi-people-fill">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi bi-people-fill">
            <path d="M13 7c0 1.657-1.343 3-3 3S7 8.657 7 7s1.343-3 3-3 3 1.343 3 3zM4 8a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm5.216 1.847A4.992 4.992 0 0 0 9 14H1a1 1 0 0 1-1-1v-1a4.978 4.978 0 0 1 3-4.576A3.999 3.999 0 0 0 4 8c0 .93.316 1.785.841 2.464A4.978 4.978 0 0 1 9 14h1a5.978 5.978 0 0 0-.784-4.153z" fill="#1E90FF"/>
          </svg>
        </i>
        <h3>Volunteer Program</h3>
        <p>Join our dedicated team of volunteers and help with feeding, cleaning, socializing, and transporting animals.</p>
      </div>
    </div>
  </div>
</section>
      
       <!-- Part5 -->
<section class="part5">
  <div class="container">
    <div class="leftRight-part">
      <div class="rightContent">
        <div class="rightContent1">
          <span>Our Mission</span>
          <h2>Compassion in Action: Saving Lives and Creating Families</h2>
          <p>At our animal shelter, we believe every life matters. We rescue, care for, and find loving homes for animals in need. Through dedicated volunteers, medical support, and awareness campaigns, we ensure each animal gets the chance they deserve.</p>
        </div>
        <div class="rightcontent">
          <div class="rightContent2">
            <i class="bi bi-house-heart-fill">
              <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-house-heart-fill" viewBox="0 0 16 16"  width="32" height="32">
                <path d="M8 3.293l6 6V15a1 1 0 0 1-1 1h-4v-4H7v4H3a1 1 0 0 1-1-1V9.293l6-6zm0-2.586L.354 8.354a.5.5 0 1 0 .708.708L8 1.707l6.938 7.355a.5.5 0 1 0 .708-.708L8 .707z" fill="#f675a8"/>
                <path d="M8 6.5c.5-.5 1.5-.5 2 0s.5 1.5 0 2l-2 2-2-2a1.5 1.5 0 1 1 2-2z" fill="#f675a8"/>
              </svg>
            </i>
            <h3>Rescue & Rehoming</h3>
            <p>We rescue abandoned and mistreated animals, giving them a safe space and helping them find loving families.</p>
          </div>
          <div class="rightContent2">
            <i class="bi bi-shield-plus">
              <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-shield-plus" viewBox="0 0 16 16"  width="32" height="32">
                <path d="M5.5 8a.5.5 0 0 1 .5-.5H7V6a.5.5 0 0 1 1 0v1.5h1a.5.5 0 0 1 0 1H8V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1-.5-.5z" fill="#36a2eb"/>
                <path d="M8 0c-.69 0-1.37.063-2.035.186-.389.073-.77.166-1.145.278a.997.997 0 0 0-.68.607 13.538 13.538 0 0 0-.593 3.265C3.233 6.952 4.792 9.754 8 15c3.208-5.246 4.767-8.048 4.453-10.664a13.538 13.538 0 0 0-.593-3.265.997.997 0 0 0-.68-.607 10.978 10.978 0 0 0-1.145-.278A10.976 10.976 0 0 0 8 0z" fill="#36a2eb"/>
              </svg>
            </i>
            <h3>Health & Safety</h3>
            <p>With on-site medical care and regular vaccinations, we ensure the physical and emotional well-being of every animal.</p>
          </div>
        </div>
      </div>
      <div class="leftContent">
        <img src="images/dog1.jpg" alt="Animal Care at Shelter">
      </div>
    </div>
  </div>
</section>

        <!-- Part7 -->
<section class="part7 py-5">
  <div class="container" style="margin: 0">
    <div class="mainDIV">
      <div class="headerDIV text-center mb-4">
        <h2>Our Animals</h2>
      </div>

      <div class="cardDiv d-flex justify-content-center gap-4 flex-wrap">
        @foreach($animals as $animal)
          <div class="cards card shadow" style="width: 18rem;">
            <img src="{{ $animal->image === 'images/animal.jpg' 
                        ? asset('images/animal.jpg') 
                        : asset('storage/' . $animal->image) }}" 
                 class="card-img-top" style="height: 250px; object-fit: cover;"
                 alt="{{ $animal->animal_name }}">

            <div class="card-body text-center">
              <h3 class="card-title">{{ $animal->animal_name }}</h3>
              <p class="card-text">Gender: {{ $animal->gender }}</p>
              <p class="card-text">Type: {{ $animal->type }}</p>
              <p class="card-text">Birth Date: {{ $animal->birth_date }}</p>
              <a href="{{url('/index')}}" class="btn btn-primary mt-2">More Animals</a>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </div>
</section>

        <script src="animation.js"></script>
        <!-- Footer -->
    @include('partials.footer')
</body>
</html>