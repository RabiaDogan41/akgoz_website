// Hamburger menü işlevselliği
document.addEventListener('DOMContentLoaded', function() {
    const menuIcon = document.querySelector('.menu-icon');
    const menuIconElement = document.querySelector('.menu-icon i');
    const navLinks = document.querySelector('.nav-links');
    
    menuIcon.addEventListener('click', function() {
        // Toggle nav-links görünürlüğü
        navLinks.classList.toggle('active');
        
        // Menu icon'u X işaretine dönüştür veya geri çevir
        if (menuIconElement.classList.contains('fa-bars')) {
            menuIconElement.classList.remove('fa-bars');
            menuIconElement.classList.add('fa-times');
        } else {
            menuIconElement.classList.remove('fa-times');
            menuIconElement.classList.add('fa-bars');
        }
    });
    
    // Sayfa kaydırıldığında menüyü kapat ve ikonu sıfırla
    window.addEventListener('scroll', function() {
        if (navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
            menuIconElement.classList.remove('fa-times');
            menuIconElement.classList.add('fa-bars');
        }
    });
    
    // Slider fonksiyonlarını başlat
    initSlider();
});

// Slider İşlevselliği
let slideIndex = 1;
let slideTimer;

function initSlider() {
    showSlides(slideIndex);
    autoSlide();
}

// Otomatik Geçiş
function autoSlide() {
    clearTimeout(slideTimer);
    slideTimer = setTimeout(function() {
        plusSlides(1);
    }, 5000); // 5 saniyede bir geçiş
}

// İleri/Geri Kontrolleri
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Nokta Kontrolleri
function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");
    
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    
    // Otomatik geçişi yeniden başlat
    autoSlide();
}