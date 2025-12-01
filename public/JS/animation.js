document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const backgroundContainer = document.createElement("div");
    backgroundContainer.classList.add("floating-background");
    body.prepend(backgroundContainer);

    const pawCount = 20;
    const pawColors = ["#1E90FF", "#4682B4", "#87CEFA", "#00BFFF", "#4169E1"];

    for (let i = 0; i < pawCount; i++) {
        let paw = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        paw.setAttribute("viewBox", "0 0 64 64");
        paw.setAttribute("class", "floating-paw");
        paw.innerHTML = `<path d="M32 50c-6 0-12 5-14 4-2-2-3-7-1-10 1-3 4-4 7-4 2 0 3 1 5 1s3-1 5-1c3 0 6 1 7 4 2 3 1 8-1 10-2 1-8-4-14-4zM10 34c-4-1-6-5-5-9s5-6 9-5 6 5 5 9-5 6-9 5zm44 0c4-1 6-5 5-9s-5-6-9-5-6 5-5 9 5 6 9 5zM19 26c-3-2-4-6-3-9 2-4 7-5 10-4 4 1 5 6 4 10-2 3-7 5-11 3zm26 0c-4 2-9 0-11-3-1-4 0-9 4-10 3-1 8 0 10 4 1 3 0 7-3 9z"/>`;

        let randomColor = pawColors[Math.floor(Math.random() * pawColors.length)];
        paw.style.fill = randomColor;

        let posX = Math.random() * window.innerWidth;
        let posY = Math.random() * window.innerHeight;
        paw.style.left = `${posX}px`;
        paw.style.top = `${posY}px`;

        let duration = Math.random() * 3 + 2;
        paw.style.animation = `floatRandom ${duration}s infinite ease-in-out alternate`;

        backgroundContainer.appendChild(paw);
    }
});
// Show or hide the scroll button based on scroll position
window.onscroll = function() {
    let scrollButton = document.querySelector('.scroll-button');
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollButton.style.display = "block"; // Show button
    } else {
        scrollButton.style.display = "none"; // Hide button
    }
};

// Smooth scroll to the top when the button is clicked
document.querySelector('.scroll-button').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default anchor behavior
    window.scrollTo({ top: 0, behavior: 'smooth' }); // Smooth scroll to top
});

