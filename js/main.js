// DOM Elements
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const mainNav = document.querySelector('.main-nav');
const navLinks = document.querySelectorAll('.nav-link');
const backToTopBtn = document.querySelector('.back-to-top');
const header = document.querySelector('.header');

// Mobile Menu Toggle
mobileMenuBtn.addEventListener('click', () => {
    mobileMenuBtn.classList.toggle('active');
    mainNav.classList.toggle('active');
    document.body.classList.toggle('no-scroll');
});

// Close mobile menu when clicking on a nav link
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (mainNav.classList.contains('active')) {
            mobileMenuBtn.classList.remove('active');
            mainNav.classList.remove('active');
            document.body.classList.remove('no-scroll');
        }
    });
});

// Header scroll effect
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }

    // Show/hide back to top button
    if (window.scrollY > 300) {
        backToTopBtn.classList.add('visible');
    } else {
        backToTopBtn.classList.remove('visible');
    }
});

// Back to top button
backToTopBtn.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            const headerOffset = 80;
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Form submission handling
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        alert('Thank you for your message! We will get back to you soon.');
        this.reset();
    });
}

// Newsletter subscription
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        // Add your newsletter subscription logic here
        alert(`Thank you for subscribing with ${email}!`);
        this.reset();
    });
}

// Initialize Google Maps
function initMap() {
    // Replace with your business coordinates
    const businessLocation = { lat: 40.7128, lng: -74.0060 };
    
    // Create a map centered at your business location
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: businessLocation,
        styles: [
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
            },
            {
                "featureType": "all",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{"color": "#dedede"}, {"lightness": 21}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{"color":"#800020"}, {"lightness": 17}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color":"#800020"}, {"lightness": 29}, {"weight": 0.2}]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{"color":"#ffffff"}, {"lightness": 18}]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color":"#ffffff"}, {"lightness": 16}]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{"color":"#f2f2f2"}, {"lightness": 19}]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color":"#e9e9e9"}, {"lightness": 17}]
            }
        ]
    });
    
    // Add a marker at your business location
    new google.maps.Marker({
        position: businessLocation,
        map: map,
        title: 'Your Business Name',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });
}

// Call initMap when the page loads
window.addEventListener('DOMContentLoaded', () => {
    // Add animation to elements with the animate-fadeInUp class
    const animateElements = document.querySelectorAll('.animate-fadeInUp');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
});
