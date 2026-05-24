<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="bn" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Community Blog - Fuyad Computer & Stationery</title>
    <link rel="shortcut icon" href="Logo/logo.png" type="image/png">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #475569;
        }
        
        [data-theme="dark"] {
            --gray-50: #0f172a;
            --gray-100: #1e293b;
            --gray-200: #334155;
            --gray-300: #475569;
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-50);
            color: var(--text-primary);
            transition: all 0.3s;
        }
        
        /* Header Styles */
        .blog-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(56, 189, 248, 0.2);
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-area img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #3b82f6;
        }
        
        .logo-area h1 {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #3b82f6);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
        }
        
        .nav-links a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 40px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
        }
        
        /* Hero Section */
        .blog-hero {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 4rem 2rem;
            text-align: center;
            color: white;
        }
        
        .blog-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .blog-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* Container - Two Columns */
        .blog-container {
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            align-items: start;
        }
        
        /* Posts Container - Scrollable */
        .posts-container {
            max-height: calc(100vh - 280px);
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .posts-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .posts-container::-webkit-scrollbar-track {
            background: var(--gray-200);
            border-radius: 10px;
        }
        
        .posts-container::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        /* Sidebar - Sticky */
        .sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        /* Sidebar Card */
        .sidebar-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        
        .sidebar-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-card h3 {
            font-size: 1.2rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
        }
        
        /* Post Card */
        .post-card {
            background: var(--card-bg);
            border-radius: 24px;
            margin-bottom: 2rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid var(--gray-200);
        }
        
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .post-header {
            padding: 1.5rem 1.5rem 0;
        }
        
        .post-author {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .author-info h4 {
            font-size: 1rem;
            margin-bottom: 4px;
        }
        
        .author-rank {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .rank-gold {
            background: #f59e0b;
            color: white;
        }
        
        .rank-silver {
            background: #94a3b8;
            color: white;
        }
        
        .rank-bronze {
            background: #cd7f32;
            color: white;
        }
        
        .rank-new {
            background: #64748b;
            color: white;
        }
        
        .post-date {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }
        
        .post-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .post-content {
            padding: 0 1.5rem 1rem;
            line-height: 1.7;
            color: var(--text-secondary);
        }
        
        .post-stats {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            border-bottom: 1px solid var(--gray-200);
            flex-wrap: wrap;
        }
        
        .stat-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--text-secondary);
            transition: 0.3s;
            padding: 5px 12px;
            border-radius: 30px;
        }
        
        .stat-btn:hover {
            background: var(--gray-100);
        }
        
        .stat-btn.liked {
            color: #ef4444;
        }
        
        .stat-btn i {
            font-size: 1.1rem;
        }
        
        .share-btn-group {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }
        
        .share-btn {
            background: var(--gray-100);
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .share-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Comments Section */
        .comments-section {
            padding: 1rem 1.5rem 1.5rem;
        }
        
        .comment-input-area {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        
        .comment-input-area input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid var(--gray-200);
            border-radius: 30px;
            background: var(--gray-50);
            color: var(--text-primary);
        }
        
        .comment-input-area button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .comment-input-area button:hover {
            background: var(--primary-dark);
        }
        
        .comment-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .comment-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .comment-text {
            flex: 1;
        }
        
        .comment-text strong {
            font-size: 0.8rem;
        }
        
        .comment-text small {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-left: 8px;
        }
        
        .comment-text p {
            font-size: 0.85rem;
            margin-top: 4px;
            color: var(--text-secondary);
        }
        
        /* Ranking List */
        .ranking-list {
            list-style: none;
        }
        
        .ranking-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .rank-number {
            width: 28px;
            height: 28px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }
        
        .rank-info {
            flex: 1;
        }
        
        .rank-name {
            font-weight: 600;
        }
        
        .rank-points {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }
        
        /* Share Form */
        #sharePostForm input,
        #sharePostForm textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 30px;
            border: 1px solid var(--gray-200);
            background: var(--gray-50);
            color: var(--text-primary);
            font-family: inherit;
        }
        
        #sharePostForm textarea {
            border-radius: 20px;
            resize: vertical;
        }
        
        #sharePostForm button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        
        #sharePostForm button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }
        
        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 999;
            transition: 0.3s;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
        }
        
        /* Footer */
        .footer {
            background: var(--gray-900);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }
        
        .footer p {
            opacity: 0.8;
        }
        
        /* No Posts */
        .no-posts {
            text-align: center;
            padding: 60px;
            background: var(--card-bg);
            border-radius: 24px;
        }
        
        .no-posts i {
            font-size: 3rem;
            opacity: 0.5;
            margin-bottom: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .blog-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: static;
                order: -1;
            }
            
            .posts-container {
                max-height: none;
                overflow-y: visible;
            }
            
            .blog-hero h1 {
                font-size: 2rem;
            }
            
            .blog-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .post-stats {
                flex-wrap: wrap;
            }
            
            .share-btn-group {
                margin-left: 0;
            }
        }
        
        @media (max-width: 480px) {
            .blog-container {
                padding: 0 1rem;
            }
            
            .post-title {
                font-size: 1.2rem;
            }
            
            .stat-btn {
                padding: 5px 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="blog-header">
    <div class="logo-area">
        <img src="Logo/logo.png" alt="Logo" onerror="this.src='https://placehold.co/50x50'">
        <h1>Fuyad Computer & Stationery</h1>
    </div>
    <div class="nav-links">
        <a href="index.html"><i class="fas fa-home"></i> Home</a>
        <a href="jobs.php"><i class="fas fa-briefcase"></i> Jobs</a>
        <a href="blog.php" class="active"><i class="fas fa-newspaper"></i> Blog</a>
    </div>
</header>

<!-- Hero Section -->
<section class="blog-hero">
    <h1 data-aos="fade-up"><i class="fas fa-newspaper"></i> Community Blog</h1>
    <p data-aos="fade-up" data-aos-delay="100">Share your knowledge • Get ranked • Earn recognition</p>
</section>

<!-- Main Container -->
<div class="blog-container">
    <!-- Left: Posts Container (Scrollable) -->
    <div class="posts-container" id="postsContainer">
        <!-- Posts will be loaded here -->
    </div>

    <!-- Right: Sidebar (Sticky) -->
    <div class="sidebar">
        <!-- Top Contributors Card -->
        <div class="sidebar-card" data-aos="fade-left">
            <h3><i class="fas fa-trophy"></i> Top Contributors</h3>
            <ul class="ranking-list" id="rankingList">
                <li style="justify-content:center;">Loading...</li>
            </ul>
        </div>
        
        <!-- Share Your Story Card -->
        <div class="sidebar-card" data-aos="fade-left" data-aos-delay="100">
            <h3><i class="fas fa-pen-alt"></i> Share Your Story</h3>
            <form id="sharePostForm">
                <input type="text" id="shareTitle" placeholder="📝 Post Title" required>
                <textarea id="shareContent" rows="5" placeholder="Write your blog content here..." required></textarea>
                <input type="text" id="shareAuthor" placeholder="👤 Your Name" required>
                <button type="submit"><i class="fas fa-paper-plane"></i> Submit for Approval</button>
            </form>
            <p style="font-size: 0.7rem; margin-top: 12px; text-align: center; color: var(--text-secondary);">
                <i class="fas fa-clock"></i> Your post will be visible after admin approval
            </p>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 Fuyad Computer And Stationery. All rights reserved.</p>
</footer>

<!-- Theme Toggle Button -->
<button class="theme-toggle" id="themeToggle" aria-label="Toggle Theme">
    <i class="fas fa-moon"></i>
</button>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
    
    // ========== THEME TOGGLE ==========
    function initTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.querySelector('#themeToggle i').className = 'fas fa-sun';
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            document.querySelector('#themeToggle i').className = 'fas fa-moon';
        }
    }
    
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            document.querySelector('#themeToggle i').className = 'fas fa-moon';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            document.querySelector('#themeToggle i').className = 'fas fa-sun';
        }
    }
    
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) themeBtn.onclick = toggleTheme;
    initTheme();
    
    // ========== USER SETUP ==========
    let currentUser = localStorage.getItem('blogUser');
    if (!currentUser) {
        currentUser = 'User_' + Math.floor(Math.random() * 10000);
        localStorage.setItem('blogUser', currentUser);
    }
    
    // ========== HELPER FUNCTIONS ==========
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function getRankClass(points) {
        if (points >= 50) return 'rank-gold';
        if (points >= 20) return 'rank-silver';
        if (points >= 5) return 'rank-bronze';
        return 'rank-new';
    }
    
    function getRankName(points) {
        if (points >= 50) return '🏆 Gold Creator';
        if (points >= 20) return '🥈 Silver Creator';
        if (points >= 5) return '🥉 Bronze Creator';
        return '🌱 New Contributor';
    }
    
    // ========== LOAD POSTS ==========
    let allPosts = [];
    
    async function loadPosts() {
        try {
            const response = await fetch('mfa_fuyad_compute_site/blog_api.php');
            const data = await response.json();
            if (data.success) {
                allPosts = data.data;
                renderPosts();
                renderRanking();
            } else {
                showNoPosts();
            }
        } catch (e) {
            console.error('Error loading posts:', e);
            showNoPosts();
        }
    }
    
    function showNoPosts() {
        const container = document.getElementById('postsContainer');
        container.innerHTML = `
            <div class="no-posts">
                <i class="fas fa-newspaper"></i>
                <h3>No Posts Yet</h3>
                <p>Be the first to share your story!</p>
            </div>
        `;
    }
    
    function renderPosts() {
        const container = document.getElementById('postsContainer');
        
        if (!allPosts.length) {
            showNoPosts();
            return;
        }
        
        container.innerHTML = allPosts.map(post => {
            const likes = JSON.parse(localStorage.getItem(`likes_${post.id}`) || '[]');
            const isLiked = likes.includes(currentUser);
            const comments = JSON.parse(localStorage.getItem(`comments_${post.id}`) || '[]');
            const rankClass = getRankClass(post.author_points || 0);
            const rankName = getRankName(post.author_points || 0);
            const postUrl = encodeURIComponent(window.location.href);
            const postTitle = encodeURIComponent(post.title);
            
            return `
                <div class="post-card" data-post-id="${post.id}" data-aos="fade-up">
                    <div class="post-header">
                        <div class="post-author">
                            <div class="author-avatar">${escapeHtml(post.author.charAt(0).toUpperCase())}</div>
                            <div class="author-info">
                                <h4>${escapeHtml(post.author)}</h4>
                                <span class="author-rank ${rankClass}">${rankName}</span>
                                <div class="post-date"><i class="far fa-calendar-alt"></i> ${post.created_at}</div>
                            </div>
                        </div>
                        <h2 class="post-title">${escapeHtml(post.title)}</h2>
                    </div>
                    <div class="post-content">
                        <p>${escapeHtml(post.content).replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="post-stats">
                        <button class="stat-btn like-btn ${isLiked ? 'liked' : ''}" data-id="${post.id}">
                            <i class="fa-${isLiked ? 'solid' : 'regular'} fa-heart"></i>
                            <span class="like-count">${likes.length}</span>
                        </button>
                        <button class="stat-btn comment-toggle" data-id="${post.id}">
                            <i class="fa-regular fa-comment"></i>
                            <span class="comment-count">${comments.length}</span>
                        </button>
                        <div class="share-btn-group">
                            <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u=${postUrl}" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="share-btn" href="https://twitter.com/intent/tweet?text=${postTitle}&url=${postUrl}" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="share-btn" href="https://wa.me/?text=${postTitle} - ${decodeURIComponent(postUrl)}" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                    <div class="comments-section" id="comments-${post.id}" style="display:none;">
                        <div class="comment-input-area">
                            <input type="text" id="commentInput-${post.id}" placeholder="Write a comment..." maxlength="200">
                            <button onclick="addComment(${post.id})">Post</button>
                        </div>
                        <div class="comment-list" id="commentList-${post.id}">
                            ${comments.map(c => `
                                <div class="comment-item">
                                    <div class="comment-avatar">${escapeHtml(c.user.charAt(0).toUpperCase())}</div>
                                    <div class="comment-text">
                                        <strong>${escapeHtml(c.user)}</strong> <small>${c.time}</small>
                                        <p>${escapeHtml(c.text)}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        // Attach like events
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.onclick = () => handleLike(parseInt(btn.dataset.id));
        });
        
        // Attach comment toggle events
        document.querySelectorAll('.comment-toggle').forEach(btn => {
            btn.onclick = () => {
                const id = btn.dataset.id;
                const sec = document.getElementById(`comments-${id}`);
                if (sec) {
                    sec.style.display = sec.style.display === 'none' ? 'block' : 'none';
                }
            };
        });
    }
    
    // ========== LIKE FUNCTION ==========
    function handleLike(postId) {
        let likes = JSON.parse(localStorage.getItem(`likes_${postId}`) || '[]');
        const post = allPosts.find(p => p.id == postId);
        
        if (likes.includes(currentUser)) {
            likes = likes.filter(u => u !== currentUser);
        } else {
            likes.push(currentUser);
            // Add points to author if not self-liking
            if (post && post.author !== currentUser) {
                updateAuthorPoints(post.author, 1);
            }
        }
        localStorage.setItem(`likes_${postId}`, JSON.stringify(likes));
        renderPosts();
    }
    
    // ========== COMMENT FUNCTION ==========
    function addComment(postId) {
        const input = document.getElementById(`commentInput-${postId}`);
        const text = input.value.trim();
        if (!text) return;
        
        let comments = JSON.parse(localStorage.getItem(`comments_${postId}`) || '[]');
        const post = allPosts.find(p => p.id == postId);
        
        comments.push({
            user: currentUser,
            text: text,
            time: new Date().toLocaleString()
        });
        
        localStorage.setItem(`comments_${postId}`, JSON.stringify(comments));
        input.value = '';
        
        // Add points to author for comment
        if (post && post.author !== currentUser) {
            updateAuthorPoints(post.author, 0.5);
        }
        
        renderPosts();
    }
    
    // ========== AUTHOR POINTS SYSTEM ==========
    function updateAuthorPoints(authorName, points) {
        let pointsMap = JSON.parse(localStorage.getItem('authorPoints') || '{}');
        pointsMap[authorName] = (pointsMap[authorName] || 0) + points;
        localStorage.setItem('authorPoints', JSON.stringify(pointsMap));
        renderRanking();
    }
    
    // ========== RENDER RANKING ==========
    function renderRanking() {
        let pointsMap = JSON.parse(localStorage.getItem('authorPoints') || '{}');
        
        // Include all post authors
        allPosts.forEach(post => {
            if (!pointsMap[post.author]) pointsMap[post.author] = 0;
        });
        
        const sorted = Object.entries(pointsMap)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10);
        
        const rankList = document.getElementById('rankingList');
        
        if (sorted.length === 0) {
            rankList.innerHTML = '<li style="justify-content:center;">No contributors yet</li>';
            return;
        }
        
        rankList.innerHTML = sorted.map(([name, pts], idx) => `
            <li>
                <div class="rank-number">${idx + 1}</div>
                <div class="rank-info">
                    <div class="rank-name">${escapeHtml(name)}</div>
                    <div class="rank-points">${pts} points • ${getRankName(pts)}</div>
                </div>
            </li>
        `).join('');
    }
    
    // ========== SUBMIT NEW POST ==========
    const shareForm = document.getElementById('sharePostForm');
    if (shareForm) {
        shareForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const title = document.getElementById('shareTitle').value.trim();
            const content = document.getElementById('shareContent').value.trim();
            const author = document.getElementById('shareAuthor').value.trim();
            
            if (!title || !content || !author) {
                alert('Please fill all fields!');
                return;
            }
            
            const submitBtn = shareForm.querySelector('button');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            
            try {
                const response = await fetch('mfa_fuyad_compute_site/blog_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        title: title,
                        content: content,
                        author: author,
                        status: 'pending'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Thank you! Your post has been submitted for admin approval.');
                    shareForm.reset();
                    loadPosts(); // Refresh to show new post if approved instantly
                } else {
                    alert('❌ Failed to submit. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit for Approval';
            }
        });
    }
    
    // ========== INITIAL LOAD ==========
    loadPosts();
    
    // Make functions global for onclick
    window.addComment = addComment;
</script>
</body>
</html>