<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Job Circular - Fuyad Computer | Neon Career Hub</title>
    <link rel="shortcut icon" href="Logo/logo.png" type="image/png">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --neon-primary: #00f3ff;
            --neon-secondary: #ff00ff;
            --neon-green: #00ff88;
            --neon-yellow: #ffcc00;
            --neon-red: #ff0033;
            --dark-bg: #0a0a0a;
            --dark-card: #111111;
            --dark-border: #222222;
            --text-dim: #888888;
            --text-light: #eeeeee;
        }
        
        [data-theme="light"] {
            --dark-bg: #f0f0f0;
            --dark-card: #ffffff;
            --text-dim: #666666;
            --text-light: #333333;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
        }
        
        /* Neon Glow */
        .neon-text {
            text-shadow: 0 0 5px var(--neon-primary), 0 0 10px var(--neon-primary);
        }
        
        .neon-border {
            border: 1px solid var(--neon-primary);
            box-shadow: 0 0 5px var(--neon-primary);
        }
        
        /* Header */
        .job-header {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--neon-primary);
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--neon-primary);
            box-shadow: 0 0 10px var(--neon-primary);
        }
        
        .logo-text h1 {
            font-size: 1.2rem;
            background: linear-gradient(135deg, var(--neon-primary), var(--neon-secondary));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .logo-text p {
            font-size: 0.7rem;
            color: var(--text-dim);
        }
        
        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            transition: 0.3s;
            font-size: 0.9rem;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid var(--neon-primary);
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
            color: var(--neon-primary);
        }
        
        .menu-toggle {
            display: none;
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid var(--neon-primary);
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            color: var(--neon-primary);
        }
        
        /* Hero Section */
        .jobs-hero {
            background: radial-gradient(circle at 50% 50%, rgba(0, 243, 255, 0.1), transparent 70%);
            padding: 80px 20px;
            text-align: center;
        }
        
        .jobs-hero h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--neon-primary), var(--neon-secondary));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .jobs-hero p {
            font-size: 1.1rem;
            color: var(--text-dim);
        }
        
        /* Filters Bar */
        .filters-bar {
            max-width: 1200px;
            margin: -30px auto 0;
            padding: 0 20px;
        }
        
        .filters-card {
            background: var(--dark-card);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.1);
        }
        
        .search-box {
            flex: 2;
            display: flex;
            gap: 10px;
        }
        
        .search-box input {
            flex: 1;
            padding: 12px 18px;
            border: 1px solid var(--neon-primary);
            border-radius: 50px;
            background: var(--dark-bg);
            color: var(--text-light);
            font-size: 14px;
        }
        
        .search-box input:focus {
            outline: none;
            box-shadow: 0 0 10px var(--neon-primary);
        }
        
        .filter-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-group select {
            padding: 12px 18px;
            border: 1px solid var(--neon-primary);
            border-radius: 50px;
            background: var(--dark-bg);
            color: var(--text-light);
            cursor: pointer;
        }
        
        /* Jobs Container */
        .jobs-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        /* Job Card Neon */
        .job-card {
            background: var(--dark-card);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }
        
        .job-card:hover {
            border-color: var(--neon-primary);
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);
            transform: translateY(-5px);
        }
        
        .job-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 600;
            z-index: 2;
        }
        
        .job-badge.govt {
            background: var(--neon-primary);
            color: #0a0a0a;
            box-shadow: 0 0 10px var(--neon-primary);
        }
        
        .job-badge.private {
            background: var(--neon-green);
            color: #0a0a0a;
            box-shadow: 0 0 10px var(--neon-green);
        }
        
        .job-badge.ngo {
            background: var(--neon-yellow);
            color: #0a0a0a;
            box-shadow: 0 0 10px var(--neon-yellow);
        }
        
        .circular-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid rgba(0, 243, 255, 0.2);
        }
        
        .job-content {
            padding: 20px;
        }
        
        .job-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-light);
        }
        
        .company {
            color: var(--neon-primary);
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .job-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
            padding: 12px 0;
            border-top: 1px solid rgba(0, 243, 255, 0.2);
            border-bottom: 1px solid rgba(0, 243, 255, 0.2);
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-dim);
        }
        
        .deadline {
            margin: 12px 0;
            padding: 8px;
            background: rgba(255, 0, 51, 0.1);
            border: 1px solid var(--neon-red);
            border-radius: 10px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--neon-red);
        }
        
        .apply-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: linear-gradient(135deg, var(--neon-primary), var(--neon-secondary));
            color: #0a0a0a;
            padding: 12px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px var(--neon-primary);
        }
        
        .no-jobs {
            text-align: center;
            padding: 60px;
            background: var(--dark-card);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 20px;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-card);
            border-top: 1px solid var(--neon-primary);
            padding: 30px 20px;
            text-align: center;
            margin-top: 50px;
        }
        
        .footer p {
            font-size: 12px;
            opacity: 0.7;
        }
        
        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--dark-card);
            border: 1px solid var(--neon-primary);
            color: var(--neon-primary);
            border-radius: 50%;
            cursor: pointer;
            z-index: 99;
            transition: 0.3s;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px var(--neon-primary);
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .jobs-grid { grid-template-columns: 1fr; }
            .filters-card { flex-direction: column; }
            .search-box { width: 100%; }
            .filter-group { width: 100%; justify-content: space-between; }
            .job-header { padding: 15px; }
            .header-container { flex-direction: column; }
            .nav-links { display: none; flex-direction: column; width: 100%; }
            .nav-links.active { display: flex; }
            .menu-toggle { display: block; }
            .jobs-hero h1 { font-size: 2rem; }
        }
        
        @media (min-width: 769px) {
            .nav-links { display: flex !important; }
        }
    </style>
</head>
<body>

<header class="job-header">
    <div class="header-container">
        <div class="logo">
            <img src="Logo/logo.png" alt="Logo">
            <div class="logo-text">
                <h1>Fuyad Computer & Stationery</h1>
                <p>Fuyad Career Hub</p>
            </div>
        </div>
        
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="nav-links" id="navMenu">
            <a href="index.html" class="nav-link">Home</a>
            <a href="jobs.php" class="nav-link active">Jobs</a>
            <a href="blog.php" class="nav-link">Blog</a>
            <a href="shop/index.php" class="nav-link">Shop</a>
            <a href="faq.php" class="nav-link">FAQ</a>
        </div>
    </div>
</header>

<section class="jobs-hero">
    <h1><i class="fas fa-briefcase"></i> Fuyad Career Hub</h1>
    <p>Find your dream job from trusted employers | Fast • Reliable • Secure</p>
</section>

<div class="filters-bar">
    <div class="filters-card">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Search job title, company, location...">
        </div>
        <div class="filter-group">
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <option value="IT">💻 IT & Software</option>
                <option value="Bank">🏦 Bank & Finance</option>
                <option value="Govt">🏛️ Government</option>
                <option value="NGO">🤝 NGO</option>
                <option value="Education">📚 Education</option>
                <option value="Medical">🏥 Medical</option>
                <option value="Engineering">⚙️ Engineering</option>
                <option value="Marketing">📢 Marketing & Sales</option>
                <option value="HR">👥 HR & Administration</option>
                <option value="Others">📌 Others</option>
            </select>
            <select id="typeFilter">
                <option value="">All Types</option>
                <option value="govt">🏛️ সরকারি</option>
                <option value="private">🏢 বেসরকারি</option>
                <option value="ngo">🤝 এনজিও</option>
            </select>
            <select id="sortFilter">
                <option value="latest">Latest First</option>
                <option value="deadline">Deadline (Earliest)</option>
                <option value="salary_high">Salary (High to Low)</option>
            </select>
        </div>
    </div>
</div>

<div class="jobs-container">
    <div class="jobs-grid" id="jobsGrid"></div>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer And Stationery. All rights reserved.</p>
</footer>

<button class="theme-toggle" id="themeToggle">
    <i class="fas fa-moon"></i>
</button>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    // Theme Toggle
    function initTheme() {
        const saved = localStorage.getItem('theme');
        const btn = document.getElementById('themeToggle');
        if (saved === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            if (btn) btn.innerHTML = '<i class="fas fa-moon"></i>';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            if (btn) btn.innerHTML = '<i class="fas fa-sun"></i>';
        }
    }
    
    document.getElementById('themeToggle').onclick = () => {
        const curr = document.documentElement.getAttribute('data-theme');
        const btn = document.getElementById('themeToggle');
        if (curr === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            if (btn) btn.innerHTML = '<i class="fas fa-moon"></i>';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            if (btn) btn.innerHTML = '<i class="fas fa-sun"></i>';
        }
    };
    initTheme();

    // Mobile Menu
    document.getElementById('menuToggle')?.addEventListener('click', function() {
        document.getElementById('navMenu').classList.toggle('active');
    });

    let allJobs = [];

    function getJobTypeText(type) {
        const types = { govt: 'সরকারি', private: 'বেসরকারি', ngo: 'এনজিও', other: 'অন্যান্য' };
        return types[type] || type;
    }

    function getJobTypeClass(type) {
        const classes = { govt: 'govt', private: 'private', ngo: 'ngo', other: 'private' };
        return classes[type] || 'private';
    }

    function formatDate(date) {
        if (!date || date === '0000-00-00') return 'N/A';
        try {
            return new Date(date).toLocaleDateString('bn-BD');
        } catch(e) {
            return date;
        }
    }

    function getDaysLeft(deadline) {
        if (!deadline) return null;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const deadlineDate = new Date(deadline);
        deadlineDate.setHours(0, 0, 0, 0);
        return Math.ceil((deadlineDate - today) / (1000 * 60 * 60 * 24));
    }

    function loadJobs() {
        const grid = document.getElementById('jobsGrid');
        if (grid) grid.innerHTML = '<div class="no-jobs"><i class="fas fa-spinner fa-spin"></i><h3>Loading jobs...</h3></div>';
        
        fetch('jobs_data.php')
            .then(res => {
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    allJobs = data.data;
                    filterAndDisplay();
                } else {
                    showError('No jobs found');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showError('Error loading jobs: ' + err.message);
            });
    }

    function showError(msg) {
        const grid = document.getElementById('jobsGrid');
        if (grid) grid.innerHTML = '<div class="no-jobs"><i class="fas fa-exclamation-triangle"></i><h3>' + msg + '</h3><button onclick="location.reload()" style="margin-top:15px; padding:8px 20px; background:#00f3ff; border:none; border-radius:30px; cursor:pointer;">Reload</button></div>';
    }

    function filterAndDisplay() {
        if (!allJobs.length) {
            displayJobs([]);
            return;
        }
        
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const category = document.getElementById('categoryFilter')?.value || '';
        const jobType = document.getElementById('typeFilter')?.value || '';
        const sortBy = document.getElementById('sortFilter')?.value || 'latest';

        let filtered = allJobs.filter(job => {
            const matchesSearch = (job.title || '').toLowerCase().includes(searchTerm) || 
                                  (job.company || '').toLowerCase().includes(searchTerm) ||
                                  (job.location || '').toLowerCase().includes(searchTerm);
            const matchesCategory = !category || job.category === category;
            const matchesType = !jobType || job.job_type === jobType;
            return matchesSearch && matchesCategory && matchesType;
        });

        filtered.sort((a, b) => {
            if (sortBy === 'latest') return new Date(b.created_at || 0) - new Date(a.created_at || 0);
            if (sortBy === 'deadline') return new Date(a.application_end_date || 0) - new Date(b.application_end_date || 0);
            if (sortBy === 'salary_high') {
                let salA = parseInt(a.salary) || 0;
                let salB = parseInt(b.salary) || 0;
                return salB - salA;
            }
            return 0;
        });

        displayJobs(filtered);
    }

    function displayJobs(jobs) {
        const grid = document.getElementById('jobsGrid');
        if (!grid) return;
        
        if (jobs.length === 0) {
            grid.innerHTML = '<div class="no-jobs"><i class="fas fa-search"></i><h3>No matching jobs found</h3><p>Try different search terms or filters</p></div>';
            return;
        }

        let html = '';
        for (let i = 0; i < jobs.length; i++) {
            const job = jobs[i];
            const daysLeft = getDaysLeft(job.application_end_date);
            const jobTypeText = getJobTypeText(job.job_type);
            const jobTypeClass = getJobTypeClass(job.job_type);
            const circularImg = job.circular_image && job.circular_image !== '' ? job.circular_image : 'https://placehold.co/400x200?text=Job+Circular';

            let deadlineHtml = '';
            if (daysLeft === null) deadlineHtml = '<span>📅 No deadline specified</span>';
            else if (daysLeft < 0) deadlineHtml = '<span>⏰ Deadline expired</span>';
            else if (daysLeft === 0) deadlineHtml = '<span>📅 Last day today!</span>';
            else deadlineHtml = '<span>⏳ ' + daysLeft + ' days left</span>';

            html += '<div class="job-card" data-aos="fade-up">';
            html += '<div class="job-badge ' + jobTypeClass + '">' + jobTypeText + '</div>';
            html += '<img src="' + escapeHtml(circularImg) + '" class="circular-img" onerror="this.src=\'https://placehold.co/400x200?text=Circular\'">';
            html += '<div class="job-content">';
            html += '<h3 class="job-title">' + escapeHtml(job.title) + '</h3>';
            html += '<div class="company"><i class="fas fa-building"></i> ' + escapeHtml(job.company) + '</div>';
            html += '<div class="job-meta">';
            html += '<div class="meta-item"><i class="fas fa-map-marker-alt"></i> ' + escapeHtml(job.location || 'N/A') + '</div>';
            html += '<div class="meta-item"><i class="fas fa-tag"></i> ' + escapeHtml(job.category || 'N/A') + '</div>';
            html += '<div class="meta-item"><i class="fas fa-money-bill-wave"></i> ' + escapeHtml(job.salary || 'Negotiable') + '</div>';
            html += '<div class="meta-item"><i class="fas fa-users"></i> ' + (job.positions || 1) + ' posts</div>';
            html += '<div class="meta-item"><i class="fas fa-calendar-alt"></i> Apply: ' + formatDate(job.application_start_date) + ' - ' + formatDate(job.application_end_date) + '</div>';
            html += '</div>';
            html += '<div class="deadline">' + deadlineHtml + '</div>';
            html += '<a href="' + escapeHtml(job.apply_link) + '" target="_blank" rel="noopener noreferrer" class="apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>';
            html += '</div></div>';
        }
        grid.innerHTML = html;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Event Listeners
    document.getElementById('searchInput')?.addEventListener('input', filterAndDisplay);
    document.getElementById('categoryFilter')?.addEventListener('change', filterAndDisplay);
    document.getElementById('typeFilter')?.addEventListener('change', filterAndDisplay);
    document.getElementById('sortFilter')?.addEventListener('change', filterAndDisplay);

    loadJobs();
</script>
</body>
</html>