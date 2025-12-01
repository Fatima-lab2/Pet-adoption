<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
<footer class="footer">
    <div class="container">
        <div class="footer-content">

            <!-- Contact Info -->
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p><i class="bi bi-geo-alt"></i> Beirut, Lebanon</p>
                <p><i class="bi bi-envelope"></i> info@animalshelter.com</p>
                <p><i class="bi bi-telephone"></i> +961 76 326 848</p>
            </div>

            <!-- Social Media Icons -->
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/YouBeeAI" target="_blank" aria-label="Facebook">
                        <i class="bi bi-facebook">Facebook</i>
                    </a>
                    <a href="https://www.tiktok.com/@youbee.ai" target="_blank" aria-label="TikTok">
                        <i class="bi bi-tiktok">Tik Tok</i>
                    </a>
                    <a href="https://wa.me/0096176326848" target="_blank" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp">Whatsapp</i>
                    </a>
                </div>
            </div>
             <!-- About Section -->
             <div class="footer-section about">
                <h3>About Us</h3>
                <p>We are dedicated to rescuing, rehabilitating, and rehoming animals in need. Join us in making a difference!</p>
            </div>

            <!-- Footer Logo -->
            <div class="footer-section logo">
            <h3>Heartbeats & Pawprints </h3>
                <img src="{{ asset('images/logo.png') }}" alt="Shelter Logo" class="logo">
            </div>
        </div>

  
    </div>
</footer>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</body>
</html>
