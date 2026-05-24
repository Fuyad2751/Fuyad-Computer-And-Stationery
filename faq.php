<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="bn" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions | Fuyad Computer</title>
    <link rel="shortcut icon" href="Logo/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            transition: all 0.3s;
        }

        [data-theme="dark"] {
            background: #0f172a;
            color: #f1f5f9;
        }

        /* Header */
        .faq-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-area { display: flex; align-items: center; gap: 15px; }
        .logo-area img { width: 50px; height: 50px; border-radius: 50%; border: 2px solid #3b82f6; }
        .logo-area h1 { font-size: 1.3rem; color: white; }

        .nav-links a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 40px;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { background: #2563eb; color: white; }

        /* Hero */
        .faq-hero {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 4rem 2rem;
            text-align: center;
            color: white;
        }
        .faq-hero h1 { font-size: 3rem; margin-bottom: 1rem; }
        .faq-hero p { font-size: 1.2rem; opacity: 0.9; }

        /* Search */
        .search-container {
            max-width: 600px;
            margin: -30px auto 0;
            padding: 0 20px;
        }
        .search-box {
            background: white;
            border-radius: 60px;
            padding: 5px 5px 5px 20px;
            display: flex;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .search-box input {
            flex: 1;
            border: none;
            padding: 15px 0;
            font-size: 1rem;
            outline: none;
            background: transparent;
        }
        .search-box button {
            background: #2563eb;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        /* Categories */
        .categories {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .cat-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            background: white;
            color: #1e293b;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .cat-btn.active, .cat-btn:hover {
            background: #2563eb;
            color: white;
        }
        [data-theme="dark"] .cat-btn {
            background: #1e293b;
            color: #e2e8f0;
        }

        /* FAQ Container */
        .faq-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .faq-item {
            background: white;
            border-radius: 16px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        [data-theme="dark"] .faq-item {
            background: #1e293b;
        }
        .faq-question {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .faq-question:hover { background: rgba(37,99,235,0.05); }
        .faq-question i {
            color: #2563eb;
            transition: 0.3s;
        }
        .faq-answer {
            padding: 0 25px;
            max-height: 0;
            overflow: hidden;
            transition: 0.3s;
            border-top: 1px solid transparent;
            color: #64748b;
            line-height: 1.6;
        }
        .faq-item.active .faq-answer {
            padding: 0 25px 20px 25px;
            max-height: 500px;
            border-top-color: #e2e8f0;
        }
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        .faq-category {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            margin-right: 10px;
            background: #dbeafe;
            color: #2563eb;
        }
        [data-theme="dark"] .faq-category {
            background: #2563eb;
            color: white;
        }

        .no-result {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        [data-theme="dark"] .no-result { background: #1e293b; }

        /* Footer */
        .footer {
            background: #0f172a;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .theme-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .faq-hero h1 { font-size: 2rem; }
            .faq-header { flex-direction: column; gap: 1rem; }
            .faq-question { font-size: 0.9rem; padding: 15px; }
        }
    </style>
</head>
<body>

<header class="faq-header">
    <div class="logo-area">
        <img src="Logo/logo.png" alt="Logo">
        <h1>Fuyad Computer & Stationery</h1>
    </div>
    <div class="nav-links">
        <a href="index.html"><i class="fas fa-home"></i> Home</a>
        <a href="jobs.php"><i class="fas fa-briefcase"></i> Jobs</a>
        <a href="education.html"><i class="fas fa-graduation-cap"></i> Education</a>
        <a href="faq.php" class="active"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="blog.php"><i class="fas fa-newspaper"></i> Blog</a>
    </div>
</header>

<section class="faq-hero">
    <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions</h1>
    <p>আপনার প্রশ্নের উত্তর খুঁজুন | Find answers to your questions</p>
</section>

<div class="search-container">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 প্রশ্ন লিখুন...">
        <button onclick="searchFAQ()">সার্চ</button>
    </div>
</div>

<div class="categories" id="categories">
    <button class="cat-btn active" data-cat="all">সবগুলো</button>
    <button class="cat-btn" data-cat="general">সাধারণ</button>
    <button class="cat-btn" data-cat="service">সার্ভিস</button>
    <button class="cat-btn" data-cat="payment">পেমেন্ট</button>
    <button class="cat-btn" data-cat="technical">টেকনিক্যাল</button>
</div>

<div class="faq-container" id="faqContainer">
    <!-- FAQs will load here -->
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer And Stationery. All rights reserved.</p>
</footer>

<button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i></button>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    // Theme Toggle
    function initTheme() {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.querySelector('#themeToggle i').className = 'fas fa-sun';
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            document.querySelector('#themeToggle i').className = 'fas fa-moon';
        }
    }
    document.getElementById('themeToggle').onclick = () => {
        const curr = document.documentElement.getAttribute('data-theme');
        if (curr === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            document.querySelector('#themeToggle i').className = 'fas fa-moon';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            document.querySelector('#themeToggle i').className = 'fas fa-sun';
        }
    };
    initTheme();

    let allFaqs = [];
    let currentCategory = 'all';
    let searchTerm = '';

    function loadFAQs() {
        fetch('mfa_fuyad_compute_site/faq_api.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    allFaqs = data.data;
                    renderFAQs();
                } else {
                    document.getElementById('faqContainer').innerHTML = '<div class="no-result"><i class="fas fa-database"></i><h3>No FAQs found</h3></div>';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('faqContainer').innerHTML = '<div class="no-result"><i class="fas fa-exclamation-triangle"></i><h3>Error loading FAQs</h3></div>';
            });
    }

    function renderFAQs() {
        let filtered = allFaqs;
        
        if (currentCategory !== 'all') {
            filtered = filtered.filter(f => f.category === currentCategory);
        }
        
        if (searchTerm) {
            filtered = filtered.filter(f => 
                f.question.toLowerCase().includes(searchTerm.toLowerCase()) || 
                f.answer.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }
        
        const container = document.getElementById('faqContainer');
        
        if (filtered.length === 0) {
            container.innerHTML = '<div class="no-result"><i class="fas fa-search"></i><h3>কোনো প্রশ্ন খুঁজে পাওয়া যায়নি</h3><p>অন্য কীওয়ার্ড দিয়ে চেষ্টা করুন</p></div>';
            return;
        }
        
        container.innerHTML = filtered.map((faq, index) => `
            <div class="faq-item" data-aos="fade-up" data-aos-delay="${index * 50}">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><span class="faq-category">${getCategoryName(faq.category)}</span> ${escapeHtml(faq.question)}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    ${escapeHtml(faq.answer).replace(/\n/g, '<br>')}
                </div>
            </div>
        `).join('');
    }

    function getCategoryName(cat) {
        const names = { general: 'সাধারণ', service: 'সার্ভিস', payment: 'পেমেন্ট', technical: 'টেকনিক্যাল' };
        return names[cat] || cat;
    }

    function toggleFAQ(element) {
        const item = element.closest('.faq-item');
        item.classList.toggle('active');
    }

    function searchFAQ() {
        searchTerm = document.getElementById('searchInput').value;
        renderFAQs();
    }

    // Category filter
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-cat');
            renderFAQs();
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    loadFAQs();
</script>
</body>
</html>