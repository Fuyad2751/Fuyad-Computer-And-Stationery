// Ensure UTF-8 encoding
document.charset = "UTF-8";

// ========== PRELOADER ==========
window.addEventListener('load', function() {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.style.opacity = '0';
        setTimeout(() => preloader.remove(), 500);
    }
});

// ========== THEME TOGGLE ==========
const themeBtn = document.getElementById('themeToggle');
if (themeBtn) {
    themeBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            themeBtn.innerHTML = '<i class="fas fa-moon"></i>';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
        }
    });
}

// Load saved theme - default dark
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
    document.documentElement.setAttribute('data-theme', 'light');
    if (themeBtn) themeBtn.innerHTML = '<i class="fas fa-moon"></i>';
} else {
    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark');
    if (themeBtn) themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
}

// ========== LIVE CLOCK ==========
function updateLiveClock() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    const timeStr = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')} ${ampm}`;
    const clockSpan = document.getElementById('liveTime');
    if (clockSpan) clockSpan.textContent = timeStr;
}
setInterval(updateLiveClock, 1000);
updateLiveClock();

// ========== TYPING ANIMATION ==========
const words = ["Computer Service", "Job Portal", "NID Service", "VAT/TIN Service", "BDRIS Service", "Land Records", "Passport Service", "Print & Stationery"];
let wordIndex = 0, charIndex = 0, isDeleting = false;
const typingSpan = document.getElementById('typingText');

function typeEffect() {
    if (!typingSpan) return;
    const currentWord = words[wordIndex];
    if (isDeleting) {
        typingSpan.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typingSpan.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
    }
    let speed = isDeleting ? 50 : 100;
    if (!isDeleting && charIndex === currentWord.length) {
        isDeleting = true;
        speed = 1500;
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        speed = 500;
    }
    setTimeout(typeEffect, speed);
}
setTimeout(typeEffect, 500);

// ========== CALENDAR ==========
function generateCalendar() {
    const calendarDiv = document.getElementById('calendar');
    if (!calendarDiv) return;
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let html = `<div class="calendar-month">${monthNames[month]} ${year}</div>`;
    html += `<div class="calendar-weekday">Sun</div><div class="calendar-weekday">Mon</div><div class="calendar-weekday">Tue</div><div class="calendar-weekday">Wed</div><div class="calendar-weekday">Thu</div><div class="calendar-weekday">Fri</div><div class="calendar-weekday">Sat</div>`;
    for (let i = 0; i < firstDay; i++) html += `<div class="calendar-day"></div>`;
    for (let d = 1; d <= daysInMonth; d++) {
        const isToday = (d === today.getDate());
        html += `<div class="calendar-day ${isToday ? 'calendar-today' : ''}">${d}</div>`;
    }
    calendarDiv.innerHTML = html;
}
generateCalendar();

// ========== TRANSLATOR ==========
async function translateNow() {
    const input = document.getElementById('translateInput');
    const output = document.getElementById('translateOutput');
    if (!input || !output) return;
    const text = input.value;
    const source = document.getElementById('sourceLang')?.value || 'bn';
    const target = document.getElementById('targetLang')?.value || 'en';
    if (!text) return;
    output.value = 'Translating...';
    try {
        const res = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=${source}&tl=${target}&dt=t&q=${encodeURIComponent(text)}`);
        const data = await res.json();
        output.value = data[0].map(item => item[0]).join('');
    } catch(e) {
        output.value = 'Translation failed!';
    }
}

// ========== GALLERY ==========
let galleryPhotos = [];
let currentLightboxIndex = 0;

async function loadGallery() {
    try {
        const res = await fetch('mfa_fuyad_compute_site/gallery_api.php');
        const data = await res.json();
        if (data.success && data.data.length > 0) {
            galleryPhotos = data.data;
        } else {
            galleryPhotos = [
                { id: 1, title: "Our Shop", image_path: "https://picsum.photos/id/20/400/400", category: "shop" },
                { id: 2, title: "Computer Service", image_path: "https://picsum.photos/id/26/400/400", category: "work" },
                { id: 3, title: "Our Team", image_path: "https://picsum.photos/id/91/400/400", category: "team" }
            ];
        }
        displayGallery('all');
    } catch(e) { console.error('Gallery error:', e); }
}

function displayGallery(category) {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;
    let filtered = galleryPhotos;
    if (category !== 'all') filtered = galleryPhotos.filter(p => p.category === category);
    if (filtered.length === 0) {
        grid.innerHTML = '<div style="text-align:center; padding:40px;">No images found</div>';
        return;
    }
    grid.innerHTML = filtered.map((photo, idx) => `
        <div class="gallery-item" data-id="${photo.id}" data-index="${idx}" onclick="openLightbox(${idx})">
            <img src="${photo.image_path}" alt="${photo.title}">
        </div>
    `).join('');
}

function openLightbox(index) {
    currentLightboxIndex = index;
    const modal = document.getElementById('lightboxModal');
    const img = document.getElementById('lightboxImg');
    const caption = document.getElementById('lightboxCaption');
    if (modal && img && caption && galleryPhotos[index]) {
        img.src = galleryPhotos[index].image_path;
        caption.textContent = galleryPhotos[index].title;
        modal.style.display = 'flex';
    }
}

function closeLightbox() {
    document.getElementById('lightboxModal').style.display = 'none';
}

function nextPhoto() {
    if (currentLightboxIndex < galleryPhotos.length - 1) {
        currentLightboxIndex++;
        const img = document.getElementById('lightboxImg');
        const caption = document.getElementById('lightboxCaption');
        if (img && caption && galleryPhotos[currentLightboxIndex]) {
            img.src = galleryPhotos[currentLightboxIndex].image_path;
            caption.textContent = galleryPhotos[currentLightboxIndex].title;
        }
    }
}

function prevPhoto() {
    if (currentLightboxIndex > 0) {
        currentLightboxIndex--;
        const img = document.getElementById('lightboxImg');
        const caption = document.getElementById('lightboxCaption');
        if (img && caption && galleryPhotos[currentLightboxIndex]) {
            img.src = galleryPhotos[currentLightboxIndex].image_path;
            caption.textContent = galleryPhotos[currentLightboxIndex].title;
        }
    }
}

// Gallery category buttons
document.querySelectorAll('.gallery-cat').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.gallery-cat').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        displayGallery(this.dataset.cat);
    });
});

// ========== REVIEWS SYSTEM ==========
let currentRating = 0;
let reviewIndex = 0;
let allReviews = [];

async function loadReviews() {
    try {
        const res = await fetch('mfa_fuyad_compute_site/reviews_api.php');
        const data = await res.json();
        if (data.success && Array.isArray(data.data)) {
            allReviews = data.data;
            displayReviews();
            updateStats();
        }
    } catch(e) { console.error('Review error:', e); }
}

function displayReviews() {
    const container = document.getElementById('reviewsSlider');
    if (!container) return;
    if (!allReviews || allReviews.length === 0) {
        container.innerHTML = '<div class="review-card">No reviews yet. Be the first!</div>';
        return;
    }
    container.innerHTML = allReviews.map(review => `
        <div class="review-card">
            <div style="display:flex; justify-content:space-between;">
                <strong>${escapeHtml(review.name)}</strong>
                <span style="color:#ffcc00;">${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</span>
            </div>
            <div style="font-size:12px; color:#888; margin:5px 0;">${review.service}</div>
            <p>"${escapeHtml(review.review)}"</p>
            <small>${review.date}</small>
        </div>
    `).join('');
}

function updateStats() {
    const total = allReviews.length;
    let sum = 0;
    for (let i = 0; i < allReviews.length; i++) sum += Number(allReviews[i].rating);
    const avg = total > 0 ? (sum / total).toFixed(1) : 0;
    const fiveStar = allReviews.filter(r => Number(r.rating) === 5).length;
    document.getElementById('totalReviews').textContent = total;
    document.getElementById('avgRating').textContent = avg;
    document.getElementById('fiveStarCount').textContent = fiveStar;
}

function slideReviews(direction) {
    const container = document.getElementById('reviewsSlider');
    if (!container) return;
    const slides = container.children;
    if (!slides.length) return;
    reviewIndex += direction;
    if (reviewIndex < 0) reviewIndex = slides.length - 1;
    if (reviewIndex >= slides.length) reviewIndex = 0;
    container.scrollTo({ left: slides[reviewIndex].offsetLeft, behavior: 'smooth' });
}

function openReviewModal() {
    document.getElementById('reviewModal').style.display = 'flex';
    currentRating = 0;
    document.querySelectorAll('.rating-stars i').forEach(star => star.classList.remove('active'));
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

// Star rating
document.querySelectorAll('.rating-stars i').forEach(star => {
    star.addEventListener('click', function() {
        currentRating = parseInt(this.dataset.rating);
        document.querySelectorAll('.rating-stars i').forEach(s => {
            if (parseInt(s.dataset.rating) <= currentRating) s.classList.add('active');
            else s.classList.remove('active');
        });
    });
});

async function submitReview() {
    const name = document.getElementById('reviewName')?.value;
    const service = document.getElementById('reviewService')?.value;
    const text = document.getElementById('reviewText')?.value;
    if (!name || !service || !text) { alert('Please fill all fields'); return; }
    if (currentRating === 0) { alert('Please select a rating'); return; }
    try {
        const res = await fetch('mfa_fuyad_compute_site/reviews_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, rating: currentRating, service, review: text })
        });
        const data = await res.json();
        if (data.success) {
            alert('Thank you for your review!');
            closeReviewModal();
            loadReviews();
            document.getElementById('reviewName').value = '';
            document.getElementById('reviewService').value = '';
            document.getElementById('reviewText').value = '';
        }
    } catch(e) { alert('Failed to submit review'); }
}

// ========== SERVICE REQUEST ==========
let captchaAnswer = 0;

function generateCaptcha() {
    const num1 = Math.floor(Math.random() * 10) + 1;
    const num2 = Math.floor(Math.random() * 10) + 1;
    captchaAnswer = num1 + num2;
    const captchaSpan = document.getElementById('captchaCode');
    if (captchaSpan) captchaSpan.innerHTML = `${num1} + ${num2} = ?`;
}

function refreshCaptcha() {
    generateCaptcha();
    document.getElementById('captchaInput').value = '';
}
generateCaptcha();

const serviceForm = document.getElementById('serviceForm');
if (serviceForm) {
    serviceForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('reqName').value;
        const phone = document.getElementById('reqPhone').value;
        const email = document.getElementById('reqEmail').value;
        const service = document.getElementById('reqService').value;
        const priority = document.getElementById('reqPriority').value;
        const date = document.getElementById('reqDate').value;
        const message = document.getElementById('reqMessage').value;
        const captchaVal = parseInt(document.getElementById('captchaInput').value);
        if (!name || !phone || !service || !message) { alert('Please fill required fields'); return; }
        if (captchaVal !== captchaAnswer) { alert('Incorrect captcha'); refreshCaptcha(); return; }
        try {
            const res = await fetch('mfa_fuyad_compute_site/save_request.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, phone, email, service, priority, preferredDate: date, message })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('successModal').style.display = 'flex';
                serviceForm.reset();
                refreshCaptcha();
            }
        } catch(e) { alert('Request failed. Please try again.'); }
    });
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

// ========== MOBILE MENU ==========
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            navMenu.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (navMenu.classList.contains('active') && !navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
});

// ========== HELPER ==========
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ========== AOS INIT ==========
if (typeof AOS !== 'undefined') AOS.init({ duration: 800, once: true });

// ========== INITIAL LOADS ==========
loadGallery();
loadReviews();

console.log("Neon website loaded successfully!");