<?php
// File Converter Tool - Fuyad Computer
?>
<!DOCTYPE html>
<html lang="bn" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Converter - Fuyad Computer</title>
    <link rel="shortcut icon" href="Logo/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Header */
        .converter-header {
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
        .converter-hero {
            text-align: center;
            padding: 3rem 2rem;
            color: white;
        }
        .converter-hero h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        .converter-hero p { font-size: 1.1rem; opacity: 0.9; }

        /* Container */
        .converter-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        /* Converter Cards */
        .converters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .converter-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            transition: 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .converter-card:hover { transform: translateY(-5px); }

        .card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .card-icon i { font-size: 28px; color: white; }

        .converter-card h3 { font-size: 1.3rem; margin-bottom: 10px; color: #1e293b; }
        .converter-card p { color: #64748b; font-size: 0.85rem; margin-bottom: 20px; }

        .file-input-area {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 15px;
        }
        .file-input-area:hover { border-color: #2563eb; background: #f8fafc; }
        .file-input-area i { font-size: 40px; color: #2563eb; margin-bottom: 10px; }
        .file-input-area input { display: none; }

        .convert-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .convert-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37,99,235,0.3); }

        .result-area {
            margin-top: 15px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 12px;
            display: none;
        }
        .result-area.show { display: block; }
        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 40px;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Instructions */
        .instructions {
            background: white;
            border-radius: 24px;
            padding: 25px;
            margin-top: 30px;
        }
        .instructions h3 { margin-bottom: 15px; color: #1e293b; }
        .instructions ul { list-style: none; padding-left: 0; }
        .instructions li { padding: 8px 0; padding-left: 25px; position: relative; color: #475569; }
        .instructions li:before { content: "✓"; position: absolute; left: 0; color: #10b981; font-weight: bold; }

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

        .footer {
            background: #0f172a;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .converter-header { flex-direction: column; gap: 1rem; }
            .converter-hero h1 { font-size: 1.8rem; }
            .converters-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header class="converter-header">
    <div class="logo-area">
        <img src="Logo/logo.png" alt="Logo">
        <h1>Fuyad Computer & Stationery</h1>
    </div>
    <div class="nav-links">
        <a href="index.html"><i class="fas fa-home"></i> Home</a>
        <a href="jobs.php"><i class="fas fa-briefcase"></i> Jobs</a>
        <a href="education.html"><i class="fas fa-graduation-cap"></i> Education</a>
        <a href="converter.php" class="active"><i class="fas fa-exchange-alt"></i> Converter</a>
        <a href="faq.php"><i class="fas fa-question-circle"></i> FAQ</a>
    </div>
</header>

<section class="converter-hero">
    <h1><i class="fas fa-exchange-alt"></i> Fuyad Computer And Stationery</h1>
    <h2><i class="fas fa-exchange-alt"></i> File Converter</h2>
    <p>ছবি থেকে PDF, PDF থেকে Word, PDF থেকে ইমেজ এবং আরও অনেক কিছু</p>
</section>

<div class="converter-container">
    <div class="converters-grid">
        
        <!-- Card 1: Image to PDF -->
        <div class="converter-card" data-aos="fade-up">
            <div class="card-icon"><i class="fas fa-image"></i></div>
            <h3>🖼️ ছবি থেকে PDF</h3>
            <p>এক বা একাধিক ছবি (JPG, PNG, WEBP) কে PDF এ রূপান্তর করুন</p>
            <div class="file-input-area" onclick="document.getElementById('imageInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>ছবি নির্বাচন করুন</p>
                <small>একাধিক ছবি সিলেক্ট করতে পারেন</small>
                <input type="file" id="imageInput" accept="image/*" multiple>
            </div>
            <button class="convert-btn" onclick="convertImagesToPDF()"><i class="fas fa-sync-alt"></i> PDF তে রূপান্তর</button>
            <div id="imageResult" class="result-area"></div>
        </div>

        <!-- Card 2: PDF to Images -->
        <div class="converter-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-icon"><i class="fas fa-file-pdf"></i></div>
            <h3>📄 PDF থেকে ইমেজ</h3>
            <p>PDF ফাইলকে JPG বা PNG ইমেজে রূপান্তর করুন</p>
            <div class="file-input-area" onclick="document.getElementById('pdfToImageInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>PDF ফাইল নির্বাচন করুন</p>
                <input type="file" id="pdfToImageInput" accept=".pdf">
            </div>
            <div style="margin-bottom: 10px;">
                <select id="imageFormat" style="width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <option value="jpg">JPG ফরম্যাট</option>
                    <option value="png">PNG ফরম্যাট</option>
                </select>
            </div>
            <button class="convert-btn" onclick="convertPDFToImages()"><i class="fas fa-sync-alt"></i> ইমেজে রূপান্তর</button>
            <div id="pdfToImageResult" class="result-area"></div>
        </div>

        <!-- Card 3: PDF to Word -->
        <div class="converter-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-icon"><i class="fas fa-file-word"></i></div>
            <h3>📄 PDF থেকে Word</h3>
            <p>PDF ফাইলকে সম্পাদনাযোগ্য Word (DOCX) এ রূপান্তর করুন</p>
            <div class="file-input-area" onclick="document.getElementById('pdfToWordInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>PDF ফাইল নির্বাচন করুন</p>
                <input type="file" id="pdfToWordInput" accept=".pdf">
            </div>
            <button class="convert-btn" onclick="convertPDFToWord()"><i class="fas fa-sync-alt"></i> Word এ রূপান্তর</button>
            <div id="pdfToWordResult" class="result-area"></div>
        </div>

        <!-- Card 4: Word to PDF -->
        <div class="converter-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-icon"><i class="fas fa-file-alt"></i></div>
            <h3>📝 Word থেকে PDF</h3>
            <p>Word (DOC/DOCX) ফাইলকে PDF এ রূপান্তর করুন</p>
            <div class="file-input-area" onclick="document.getElementById('wordToPdfInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Word ফাইল নির্বাচন করুন</p>
                <input type="file" id="wordToPdfInput" accept=".doc,.docx">
            </div>
            <button class="convert-btn" onclick="convertWordToPDF()"><i class="fas fa-sync-alt"></i> PDF এ রূপান্তর</button>
            <div id="wordToPdfResult" class="result-area"></div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="instructions" data-aos="fade-up">
        <h3><i class="fas fa-info-circle"></i> ব্যবহারবিধি</h3>
        <ul>
            <li>ছবি থেকে PDF: একাধিক ছবি সিলেক্ট করে একটি PDF ফাইল তৈরি করুন</li>
            <li>PDF থেকে ইমেজ: PDF এর প্রতিটি পেজ আলাদা ইমেজ ফাইলে রূপান্তর হবে</li>
            <li>PDF থেকে Word: স্ক্যান করা PDF এর জন্য সঠিক কাজ নাও করতে পারে</li>
            <li>সব রূপান্তর বিনামূল্যে এবং নিরাপদ (ফাইল সার্ভারে সংরক্ষণ করা হয় না)</li>
            <li>ফাইলের সাইজ যত ছোট হবে, রূপান্তর তত দ্রুত হবে</li>
        </ul>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2025 Fuyad Computer And Stationery. All rights reserved.</p>
</footer>

<button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i></button>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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

    // Helper function to show loading and result
    function showLoading(containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '<div class="loading"></div> রূপান্তর করা হচ্ছে...';
        container.classList.add('show');
    }

    function showResult(containerId, message, isError = false) {
        const container = document.getElementById(containerId);
        container.innerHTML = `<div style="color: ${isError ? '#ef4444' : '#10b981'}">${message}</div>`;
        container.classList.add('show');
    }

    // 1. Images to PDF
    async function convertImagesToPDF() {
        const input = document.getElementById('imageInput');
        const files = input.files;
        
        if (files.length === 0) {
            alert('দয়া করে ছবি নির্বাচন করুন');
            return;
        }

        showLoading('imageResult');
        
        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const imgData = await readFileAsDataURL(file);
                
                const img = new Image();
                await new Promise((resolve) => {
                    img.onload = () => {
                        const imgWidth = 190;
                        const imgHeight = (img.height * imgWidth) / img.width;
                        
                        if (i > 0) pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', 10, 10, imgWidth, imgHeight);
                        resolve();
                    };
                    img.src = imgData;
                });
            }
            
            const pdfBlob = pdf.output('blob');
            const url = URL.createObjectURL(pdfBlob);
            
            document.getElementById('imageResult').innerHTML = `
                <a href="${url}" download="converted.pdf" class="download-link"><i class="fas fa-download"></i> PDF ডাউনলোড করুন</a>
                <p style="margin-top: 10px; font-size: 0.75rem;">✅ ${files.length}টি ছবি PDF এ রূপান্তরিত হয়েছে</p>
            `;
        } catch (error) {
            console.error(error);
            showResult('imageResult', '❌ রূপান্তর ব্যর্থ হয়েছে। আবার চেষ্টা করুন।', true);
        }
    }

    // 2. PDF to Images (using PDF.js)
    async function convertPDFToImages() {
        const input = document.getElementById('pdfToImageInput');
        const file = input.files[0];
        const format = document.getElementById('imageFormat').value;
        
        if (!file) {
            alert('দয়া করে PDF ফাইল নির্বাচন করুন');
            return;
        }

        showLoading('pdfToImageResult');
        
        try {
            // Using pdf.js library via CDN
            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
            
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            const numPages = pdf.numPages;
            
            const zip = new JSZip();
            let converted = 0;
            
            for (let i = 1; i <= numPages; i++) {
                const page = await pdf.getPage(i);
                const viewport = page.getViewport({ scale: 2.0 });
                const canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                const context = canvas.getContext('2d');
                
                await page.render({ canvasContext: context, viewport: viewport }).promise;
                
                const imgData = canvas.toDataURL(`image/${format}`);
                const base64Data = imgData.split(',')[1];
                zip.file(`page_${i}.${format}`, base64Data, { base64: true });
                converted++;
            }
            
            const zipBlob = await zip.generateAsync({ type: 'blob' });
            const url = URL.createObjectURL(zipBlob);
            
            document.getElementById('pdfToImageResult').innerHTML = `
                <a href="${url}" download="pdf_images.zip" class="download-link"><i class="fas fa-download"></i> ZIP ফাইল ডাউনলোড করুন</a>
                <p style="margin-top: 10px; font-size: 0.75rem;">✅ ${numPages}টি পেজ ${format.toUpperCase()} এ রূপান্তরিত হয়েছে</p>
            `;
        } catch (error) {
            console.error(error);
            showResult('pdfToImageResult', '❌ রূপান্তর ব্যর্থ হয়েছে। PDF করাপ্ট বা বড় হতে পারে।', true);
        }
    }

    // 3. PDF to Word (using external API)
    async function convertPDFToWord() {
        const input = document.getElementById('pdfToWordInput');
        const file = input.files[0];
        
        if (!file) {
            alert('দয়া করে PDF ফাইল নির্বাচন করুন');
            return;
        }

        showLoading('pdfToWordResult');
        
        // Using a free API for demo
        try {
            const formData = new FormData();
            formData.append('file', file);
            
            // Note: This is a demo API endpoint. In production, use your own backend
            const response = await fetch('https://api.pdf.co/v1/pdf/convert/to/doc', {
                method: 'POST',
                headers: { 'x-api-key': 'demo' }, // Demo key - replace with your own
                body: formData
            });
            
            if (!response.ok) throw new Error('API error');
            
            const data = await response.json();
            if (data.url) {
                document.getElementById('pdfToWordResult').innerHTML = `
                    <a href="${data.url}" download="converted.docx" class="download-link"><i class="fas fa-download"></i> Word ডাউনলোড করুন</a>
                    <p style="margin-top: 10px; font-size: 0.75rem;">✅ PDF থেকে Word এ রূপান্তর সম্পূর্ণ</p>
                `;
            } else {
                throw new Error('Conversion failed');
            }
        } catch (error) {
            console.error(error);
            // Fallback: Provide alternative solution
            document.getElementById('pdfToWordResult').innerHTML = `
                <p style="color: #f59e0b;">⚠️ অনলাইন রূপান্তর সীমিত। বিকল্প উপায়:</p>
                <p style="font-size: 0.75rem;">• <a href="https://www.ilovepdf.com/pdf_to_word" target="_blank">iLovePDF</a> ব্যবহার করুন<br>
                • <a href="https://smallpdf.com/pdf-to-word" target="_blank">SmallPDF</a> ব্যবহার করুন<br>
                • অথবা PDF ফাইলটি ইমেইল করুন, আমরা কনভার্ট করে দেবো</p>
            `;
        }
    }

    // 4. Word to PDF
    async function convertWordToPDF() {
        const input = document.getElementById('wordToPdfInput');
        const file = input.files[0];
        
        if (!file) {
            alert('দয়া করে Word ফাইল নির্বাচন করুন');
            return;
        }

        showLoading('wordToPdfResult');
        
        try {
            const formData = new FormData();
            formData.append('file', file);
            
            const response = await fetch('https://api.pdf.co/v1/pdf/convert/from/doc', {
                method: 'POST',
                headers: { 'x-api-key': 'demo' },
                body: formData
            });
            
            if (!response.ok) throw new Error('API error');
            
            const data = await response.json();
            if (data.url) {
                document.getElementById('wordToPdfResult').innerHTML = `
                    <a href="${data.url}" download="converted.pdf" class="download-link"><i class="fas fa-download"></i> PDF ডাউনলোড করুন</a>
                    <p style="margin-top: 10px; font-size: 0.75rem;">✅ Word থেকে PDF এ রূপান্তর সম্পূর্ণ</p>
                `;
            } else {
                throw new Error('Conversion failed');
            }
        } catch (error) {
            console.error(error);
            document.getElementById('wordToPdfResult').innerHTML = `
                <p style="color: #f59e0b;">⚠️ অনলাইন রূপান্তর সীমিত। বিকল্প উপায়:</p>
                <p style="font-size: 0.75rem;">• <a href="https://www.ilovepdf.com/word_to_pdf" target="_blank">iLovePDF</a> ব্যবহার করুন<br>
                • Microsoft Word এ খুলে "Save as PDF" করুন<br>
                • অথবা ফাইলটি ইমেইল করুন</p>
            `;
        }
    }

    function readFileAsDataURL(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    // Load PDF.js and JSZip libraries
    function loadScript(src) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    // Load required libraries
    Promise.all([
        loadScript('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js'),
        loadScript('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js')
    ]).catch(console.error);
</script>
</body>
</html>