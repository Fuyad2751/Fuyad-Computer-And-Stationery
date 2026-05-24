<?php
// Professional CV/Bio-Data Generator - Full Line Control
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV & Bio-Data Generator - Fuyad Computer</title>
    <link rel="shortcut icon" href="Logo/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Serif+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            padding: 20px;
        }

        .generator-container { max-width: 1400px; margin: 0 auto; }

        .header {
            background: white;
            border-radius: 20px;
            padding: 15px 25px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .logo-area { display: flex; align-items: center; gap: 15px; }
        .logo-area img { width: 45px; height: 45px; border-radius: 50%; }
        .logo-area h1 { font-size: 1.2rem; color: #1e293b; }

        .nav-links a {
            color: #64748b;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 40px;
            margin: 0 5px;
        }
        .nav-links a:hover, .nav-links a.active { background: #2563eb; color: white; }

        .two-column { display: grid; grid-template-columns: 520px 1fr; gap: 25px; }

        .form-panel {
            background: white;
            border-radius: 20px;
            padding: 20px;
            height: calc(100vh - 100px);
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .form-panel::-webkit-scrollbar { width: 5px; }
        .form-panel::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
        .form-panel::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }

        .lang-switch {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .lang-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            transition: 0.3s;
        }
        .lang-btn.active { background: #2563eb; color: white; }

        .font-controls {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .font-controls-title { font-weight: 600; margin-bottom: 12px; color: #2563eb; }
        .font-control-item { margin-bottom: 10px; }
        .font-control-item label { display: flex; justify-content: space-between; font-size: 0.75rem; }
        .font-control-item input { width: 100%; cursor: pointer; }
        .reset-font-btn { background: #64748b; color: white; border: none; padding: 8px; border-radius: 20px; cursor: pointer; width: 100%; margin-top: 10px; }

        .form-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .form-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 500; color: #64748b; margin-bottom: 3px; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.8rem;
        }

        .dynamic-item { display: flex; gap: 8px; margin-bottom: 12px; align-items: flex-start; }
        .dynamic-item input { flex: 1; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; }
        
        .add-btn { background: #10b981; color: white; border: none; padding: 5px 15px; border-radius: 20px; cursor: pointer; font-size: 0.7rem; }
        .remove-btn { background: #ef4444; color: white; border: none; padding: 5px 12px; border-radius: 20px; cursor: pointer; font-size: 0.7rem; }

        .preview-panel {
            background: #e2e8f0;
            border-radius: 20px;
            padding: 20px;
            height: calc(100vh - 100px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        .action-btn { padding: 10px 20px; border: none; border-radius: 40px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .action-btn.pdf { background: #ef4444; color: white; }
        .action-btn.print { background: #10b981; color: white; }
        .action-btn.reset-all { background: #64748b; color: white; }

        .a4-container {
            background: white;
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 4px;
            overflow: auto;
            flex: 1;
        }

        .cv-paper {
            background: white;
            padding: 0.6in;
            font-family: 'Inter', sans-serif;
            page-break-after: avoid;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .cv-paper.bangla { font-family: 'Noto Serif Bengali', 'Inter', sans-serif; }

        .cv-main-title { font-size: 24px; font-weight: 700; text-align: center; margin-bottom: 8px; }
        .cv-header-area { display: flex; justify-content: flex-end; margin-bottom: 10px; }
        .photo-section { width: 100px; text-align: center; }
        .cv-photo { width: 90px; height: 110px; object-fit: cover; border: 2px solid #2563eb; border-radius: 6px; }
        .photo-placeholder { width: 90px; height: 110px; border: 2px dashed #cbd5e1; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 10px; }
        .full-name { font-size: 20px; font-weight: 700; margin-bottom: 15px; text-align: left; }

        .cv-section { margin-bottom: 15px; page-break-inside: avoid; break-inside: avoid; }
        .cv-section-title { font-size: 16px; font-weight: 700; color: #2563eb; border-left: 3px solid #2563eb; padding-left: 8px; margin-bottom: 10px; }

        .info-single { width: 100%; }
        .info-row { display: flex; margin-bottom: 6px; flex-wrap: wrap; align-items: baseline; }
        .info-label { width: 140px; font-weight: 600; color: #475569; font-size: 15px; }
        .info-value { flex: 1; color: #1e293b; font-size: 15px; }

        .education-item { margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px dotted #e2e8f0; }
        .education-degree { font-weight: 700; font-size: 15px; }
        .education-details { font-size: 13px; color: #475569; margin-top: 3px; }

        .skill-list, .char-list { list-style: none; padding-left: 0; }
        .skill-list li, .char-list li { padding: 4px 0 4px 20px; position: relative; font-size: 14px; }
        .skill-list li:before, .char-list li:before { content: "▹"; position: absolute; left: 0; color: #2563eb; }

        .language-row { display: flex; margin-bottom: 5px; font-size: 14px; }
        .language-name { width: 100px; font-weight: 600; }
        .ref-item { margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px dotted #e2e8f0; font-size: 13px; }
        .declaration { margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 12px; text-align: center; }
        .cv-paper p { font-size: 14px; line-height: 1.5; text-align: justify; }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            .form-panel, .header, .action-buttons, .lang-switch, .font-controls, .no-print { display: none !important; }
            .two-column { display: block; }
            .preview-panel { padding: 0; background: white; height: auto; }
            .a4-container { box-shadow: none; }
            .cv-paper { padding: 0.6in; }
            .cv-section { page-break-inside: avoid; }
        }

        @media (max-width: 900px) {
            .two-column { grid-template-columns: 1fr; }
            .form-panel { height: auto; max-height: 500px; }
            .preview-panel { height: auto; }
        }
    </style>
</head>
<body>

<div class="generator-container">
    <div class="header">
        <div class="logo-area">
            <img src="Logo/logo.png" alt="Logo">
            <h1>Fuyad Computer & Stationery</h1>
        </div>
        <div class="nav-links">
            <a href="index.html"><i class="fas fa-home"></i> Home</a>
            <a href="jobs.php"><i class="fas fa-briefcase"></i> Jobs</a>
            <a href="cv-generator.php" class="active"><i class="fas fa-file-alt"></i> CV Maker</a>
        </div>
    </div>

    <div class="two-column">
        <div class="form-panel">
            <div class="lang-switch">
                <button class="lang-btn active" id="langBanglaBtn">🇧🇩 বায়োডাটা</button>
                <button class="lang-btn" id="langEnglishBtn">🇬🇧 Curriculum Vitae</button>
            </div>

            <div class="font-controls">
                <div class="font-controls-title"><i class="fas fa-text-height"></i> ফন্ট সাইজ কন্ট্রোল (15-20px)</div>
                <div class="font-control-item"><label>শিরোনাম সাইজ <span id="titleSizeVal">24px</span></label><input type="range" id="titleSize" min="18" max="32" value="24"></div>
                <div class="font-control-item"><label>নাম সাইজ <span id="nameSizeVal">20px</span></label><input type="range" id="nameSize" min="16" max="28" value="20"></div>
                <div class="font-control-item"><label>সেকশন টাইটেল <span id="sectionTitleSizeVal">16px</span></label><input type="range" id="sectionTitleSize" min="14" max="22" value="16"></div>
                <div class="font-control-item"><label>লেবেল টেক্সট <span id="labelSizeVal">15px</span></label><input type="range" id="labelSize" min="13" max="20" value="15"></div>
                <div class="font-control-item"><label>ভ্যালু টেক্সট <span id="valueSizeVal">15px</span></label><input type="range" id="valueSize" min="13" max="20" value="15"></div>
                <div class="font-control-item"><label>অন্যান্য টেক্সট <span id="textSizeVal">14px</span></label><input type="range" id="textSize" min="12" max="18" value="14"></div>
                <button class="reset-font-btn" id="resetFontBtn">ডিফল্ট সাইজ রিসেট</button>
            </div>

            <!-- Photo -->
            <div class="form-section">
                <div class="form-section-title">📷 ছবি আপলোড</div>
                <input type="file" id="photoUpload" accept="image/*">
            </div>

            <!-- Personal Info with Full Control -->
            <div class="form-section">
                <div class="form-section-title">👤 ব্যক্তিগত তথ্য</div>
                <div class="form-group"><label>পূর্ণ নাম (বাংলা)</label><input type="text" id="nameBn" value="মোছা. সামসুন্নাহার শ্রাবণী"></div>
                <div class="form-group"><label>Full Name (English)</label><input type="text" id="nameEn" value="Mst. Samsunnahar Srabony"></div>
                <div class="form-group"><label>পিতার নাম (বাংলা)</label><input type="text" id="fatherBn" value="মোঃ সাইফুল ইসলাম"></div>
                <div class="form-group"><label>Father's Name (English)</label><input type="text" id="fatherEn" value="Md. Saiful Islam"></div>
                <div class="form-group"><label>মাতার নাম (বাংলা)</label><input type="text" id="motherBn" value="মোছা. নাইস বেগম"></div>
                <div class="form-group"><label>Mother's Name (English)</label><input type="text" id="motherEn" value="Mst. Nice Begum"></div>
                <div class="form-group"><label>জন্ম তারিখ</label><input type="date" id="dob" value="2002-08-06"></div>
                <div class="form-group"><label>লিঙ্গ</label><input type="text" id="gender" value="নারী / Female"></div>
                <div class="form-group"><label>বৈবাহিক অবস্থা</label><input type="text" id="marital" value="বিবাহিত / Married"></div>
                <div class="form-group"><label>জাতীয়তা</label><input type="text" id="nationality" value="বাংলাদেশী / Bangladeshi"></div>
                <div class="form-group"><label>ধর্ম</label><input type="text" id="religion" value="ইসলাম / Islam"></div>
                <div class="form-group"><label>NID</label><input type="text" id="nid" value="4662860669"></div>
                <div class="form-group"><label>মোবাইল</label><input type="text" id="mobile" value="01784-268355"></div>
                <div class="form-group"><label>ইমেইল</label><input type="email" id="email" value="srabony@gmail.com"></div>
                <div class="form-group"><label>বর্তমান ঠিকানা</label><textarea id="presentAddress" rows="2">বড় দূর্গাপুর, তুলশীঘাট, গাইবান্ধা</textarea></div>
                <div class="form-group"><label>স্থায়ী ঠিকানা</label><textarea id="permanentAddress" rows="2">বড় দূর্গাপুর, তুলশীঘাট, গাইবান্ধা</textarea></div>
            </div>

            <!-- Education Section -->
            <div class="form-section">
                <div class="form-section-title">
                    <span>🎓 শিক্ষাগত যোগ্যতা</span>
                    <button class="add-btn" id="addEducationBtn">+ যোগ করুন</button>
                </div>
                <div id="educationList"></div>
            </div>

            <!-- Career Objective -->
            <div class="form-section">
                <div class="form-section-title">🎯 কর্মজীবনের লক্ষ্য</div>
                <textarea id="careerBn" rows="2" style="width:100%; margin-bottom:10px;" placeholder="বাংলা">কঠোর ও চ্যালেঞ্জিং চাকরির সন্ধান করছি যেখানে আমি আমার দক্ষতা ও সৃজনশীলতা ব্যবহার করতে পারব।</textarea>
                <textarea id="careerEn" rows="2" style="width:100%;" placeholder="English">Looking for hard and challenging job where I can utilize my skills and creativity.</textarea>
            </div>

            <!-- Skills -->
            <div class="form-section">
                <div class="form-section-title">💻 দক্ষতা <button class="add-btn" id="addSkillBtn">+ যোগ করুন</button></div>
                <div id="skillsList"></div>
            </div>

            <!-- Characteristics -->
            <div class="form-section">
                <div class="form-section-title">⭐ ব্যক্তিগত বৈশিষ্ট্য <button class="add-btn" id="addCharacteristicBtn">+ যোগ করুন</button></div>
                <div id="characteristicsList"></div>
            </div>

            <!-- Language -->
            <div class="form-section">
                <div class="form-section-title">🌐 ভাষার দক্ষতা</div>
                <div class="form-group"><label>বাংলা</label><select id="bengaliLevel"><option>Excellent</option><option>Good</option></select></div>
                <div class="form-group"><label>ইংরেজি</label><select id="englishLevel"><option>Good</option><option>Excellent</option></select></div>
            </div>

            <!-- Reference -->
            <div class="form-section">
                <div class="form-section-title">👥 রেফারেন্স <button class="add-btn" id="addReferenceBtn">+ যোগ করুন</button></div>
                <div id="referenceList"></div>
            </div>
        </div>

        <div class="preview-panel">
            <div class="action-buttons no-print">
                <button class="action-btn pdf" id="downloadPdfBtn"><i class="fas fa-file-pdf"></i> PDF ডাউনলোড</button>
                <button class="action-btn print" id="printBtn"><i class="fas fa-print"></i> প্রিন্ট</button>
                <button class="action-btn reset-all" id="resetAllBtn"><i class="fas fa-undo"></i> সব রিসেট</button>
            </div>
            <div class="a4-container">
                <div id="cvPreview" class="cv-paper"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // ========== DATA ==========
    let currentLang = 'bangla';
    let photoData = null;
    
    let fontSizes = { title: 24, name: 20, sectionTitle: 16, label: 15, value: 15, text: 14 };
    
    let educations = [
        { degreeBn: 'স্নাতক (বিএ)', degreeEn: 'Bachelor of Arts (BA)', institutionBn: 'তুলশীঘাট সামসুল হক ডিগ্রি কলেজ', institutionEn: 'Tulshighat Samsul Haque Degree College', boardBn: 'জাতীয় বিশ্ববিদ্যালয়', boardEn: 'National University', result: 'GPA 3.35 (Out of 4)', year: '2023' },
        { degreeBn: 'উচ্চ মাধ্যমিক (এইচএসসি)', degreeEn: 'Higher Secondary Certificate (HSC)', institutionBn: 'ফকিরহাট শহীদ স্মৃতি ডিগ্রি কলেজ', institutionEn: 'Fokirhat Shahid Smriti Degree College', boardBn: 'দিনাজপুর', boardEn: 'Dinajpur', result: 'GPA 4.33 (Out of 5)', year: '2020' },
        { degreeBn: 'মাধ্যমিক (এসএসসি)', degreeEn: 'Secondary School Certificate (SSC)', institutionBn: 'পবনাপুর এফএম হাই স্কুল', institutionEn: 'Pobnapur FM Haigh School', boardBn: 'দিনাজপুর', boardEn: 'Dinajpur', result: 'GPA 3.89 (Out of 5)', year: '2018' }
    ];
    
    let skills = ['Microsoft Office', 'Internet Browsing', 'Email Communication', 'Data Entry'];
    let characteristics = ['Self Motivated & able to take initiative', 'Able to adjust in different Environment', 'Studious, confident and can travel', 'Organizing capacity', 'Problem solving'];
    let references = [{ nameBn: 'মোঃ ফুয়াদ আকন্দ', nameEn: 'Md. Fuyad Akand', designationBn: 'কম্পিউটার অপারেটর', designationEn: 'Computer Operator', mobile: '01540103056' }];
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ========== FONT SIZE ==========
    function updateFontSize(type, val) {
        fontSizes[type] = parseInt(val);
        document.getElementById(type + 'SizeVal').innerText = val + 'px';
        generateCV();
    }
    
    function resetFontSizes() {
        fontSizes = { title: 24, name: 20, sectionTitle: 16, label: 15, value: 15, text: 14 };
        document.getElementById('titleSize').value = 24;
        document.getElementById('nameSize').value = 20;
        document.getElementById('sectionTitleSize').value = 16;
        document.getElementById('labelSize').value = 15;
        document.getElementById('valueSize').value = 15;
        document.getElementById('textSize').value = 14;
        document.getElementById('titleSizeVal').innerText = '24px';
        document.getElementById('nameSizeVal').innerText = '20px';
        document.getElementById('sectionTitleSizeVal').innerText = '16px';
        document.getElementById('labelSizeVal').innerText = '15px';
        document.getElementById('valueSizeVal').innerText = '15px';
        document.getElementById('textSizeVal').innerText = '14px';
        generateCV();
    }
    
    function uploadPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { photoData = e.target.result; generateCV(); };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function setLanguage(lang) {
        currentLang = lang;
        if (lang === 'bangla') {
            document.getElementById('langBanglaBtn').classList.add('active');
            document.getElementById('langEnglishBtn').classList.remove('active');
        } else {
            document.getElementById('langEnglishBtn').classList.add('active');
            document.getElementById('langBanglaBtn').classList.remove('active');
        }
        generateCV();
    }
    
    // ========== EDUCATION ==========
    function renderEducation() {
        let container = document.getElementById('educationList');
        container.innerHTML = educations.map((edu, idx) => `
            <div class="dynamic-item">
                <div style="flex:1">
                    <input type="text" placeholder="Degree (বাংলা)" value="${escapeHtml(edu.degreeBn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'degreeBn',this.value)">
                    <input type="text" placeholder="Degree (English)" value="${escapeHtml(edu.degreeEn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'degreeEn',this.value)">
                    <input type="text" placeholder="Institution (বাংলা)" value="${escapeHtml(edu.institutionBn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'institutionBn',this.value)">
                    <input type="text" placeholder="Institution (English)" value="${escapeHtml(edu.institutionEn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'institutionEn',this.value)">
                    <input type="text" placeholder="Board (বাংলা)" value="${escapeHtml(edu.boardBn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'boardBn',this.value)">
                    <input type="text" placeholder="Board (English)" value="${escapeHtml(edu.boardEn)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'boardEn',this.value)">
                    <input type="text" placeholder="Result" value="${escapeHtml(edu.result)}" style="width:100%; margin-bottom:5px;" onchange="updateEducation(${idx},'result',this.value)">
                    <input type="text" placeholder="Year" value="${escapeHtml(edu.year)}" style="width:100%;" onchange="updateEducation(${idx},'year',this.value)">
                </div>
                <button class="remove-btn" onclick="removeEducation(${idx})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }
    function updateEducation(idx, field, value) { educations[idx][field] = value; generateCV(); }
    function addEducation() { educations.push({ degreeBn:'', degreeEn:'', institutionBn:'', institutionEn:'', boardBn:'', boardEn:'', result:'', year:'' }); renderEducation(); generateCV(); }
    function removeEducation(idx) { educations.splice(idx,1); renderEducation(); generateCV(); }
    
    // ========== SKILLS ==========
    function renderSkills() {
        let container = document.getElementById('skillsList');
        container.innerHTML = skills.map((skill, idx) => `
            <div class="dynamic-item">
                <input type="text" value="${escapeHtml(skill)}" onchange="updateSkill(${idx},this.value)" style="flex:1">
                <button class="remove-btn" onclick="removeSkill(${idx})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }
    function updateSkill(idx, val) { skills[idx] = val; generateCV(); }
    function addSkill() { skills.push(''); renderSkills(); generateCV(); }
    function removeSkill(idx) { skills.splice(idx,1); renderSkills(); generateCV(); }
    
    // ========== CHARACTERISTICS ==========
    function renderCharacteristics() {
        let container = document.getElementById('characteristicsList');
        container.innerHTML = characteristics.map((char, idx) => `
            <div class="dynamic-item">
                <input type="text" value="${escapeHtml(char)}" onchange="updateCharacteristic(${idx},this.value)" style="flex:1">
                <button class="remove-btn" onclick="removeCharacteristic(${idx})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }
    function updateCharacteristic(idx, val) { characteristics[idx] = val; generateCV(); }
    function addCharacteristic() { characteristics.push(''); renderCharacteristics(); generateCV(); }
    function removeCharacteristic(idx) { characteristics.splice(idx,1); renderCharacteristics(); generateCV(); }
    
    // ========== REFERENCES ==========
    function renderReferences() {
        let container = document.getElementById('referenceList');
        container.innerHTML = references.map((ref, idx) => `
            <div class="dynamic-item">
                <div style="flex:1">
                    <input type="text" placeholder="Name (বাংলা)" value="${escapeHtml(ref.nameBn)}" style="width:100%; margin-bottom:5px;" onchange="updateReference(${idx},'nameBn',this.value)">
                    <input type="text" placeholder="Name (English)" value="${escapeHtml(ref.nameEn)}" style="width:100%; margin-bottom:5px;" onchange="updateReference(${idx},'nameEn',this.value)">
                    <input type="text" placeholder="Designation (বাংলা)" value="${escapeHtml(ref.designationBn)}" style="width:100%; margin-bottom:5px;" onchange="updateReference(${idx},'designationBn',this.value)">
                    <input type="text" placeholder="Designation (English)" value="${escapeHtml(ref.designationEn)}" style="width:100%; margin-bottom:5px;" onchange="updateReference(${idx},'designationEn',this.value)">
                    <input type="text" placeholder="Mobile" value="${escapeHtml(ref.mobile)}" style="width:100%;" onchange="updateReference(${idx},'mobile',this.value)">
                </div>
                <button class="remove-btn" onclick="removeReference(${idx})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }
    function updateReference(idx, field, val) { references[idx][field] = val; generateCV(); }
    function addReference() { references.push({ nameBn:'', nameEn:'', designationBn:'', designationEn:'', mobile:'' }); renderReferences(); generateCV(); }
    function removeReference(idx) { references.splice(idx,1); renderReferences(); generateCV(); }
    
    // ========== GENERATE CV ==========
    function generateCV() {
        let name = currentLang == 'bangla' ? document.getElementById('nameBn').value : document.getElementById('nameEn').value;
        let father = currentLang == 'bangla' ? document.getElementById('fatherBn').value : document.getElementById('fatherEn').value;
        let mother = currentLang == 'bangla' ? document.getElementById('motherBn').value : document.getElementById('motherEn').value;
        let dob = document.getElementById('dob').value;
        let gender = document.getElementById('gender').value;
        let marital = document.getElementById('marital').value;
        let nationality = document.getElementById('nationality').value;
        let religion = document.getElementById('religion').value;
        let nid = document.getElementById('nid').value;
        let mobile = document.getElementById('mobile').value;
        let email = document.getElementById('email').value;
        let presentAddr = document.getElementById('presentAddress').value;
        let permanentAddr = document.getElementById('permanentAddress').value;
        let careerObj = currentLang == 'bangla' ? document.getElementById('careerBn').value : document.getElementById('careerEn').value;
        let bengaliLevel = document.getElementById('bengaliLevel').value;
        let englishLevel = document.getElementById('englishLevel').value;
        let formattedDob = new Date(dob).toLocaleDateString();
        let title = currentLang == 'bangla' ? 'বায়োডাটা' : 'Curriculum Vitae';
        
        let html = `<div class="cv-paper ${currentLang == 'bangla' ? 'bangla' : ''}">
            <div class="cv-main-title" style="font-size:${fontSizes.title}px;">${title}</div>
            <div class="cv-header-area"><div class="photo-section">${photoData ? `<img src="${photoData}" class="cv-photo">` : '<div class="photo-placeholder"><i class="fas fa-user-circle fa-2x"></i><br>ছবি</div>'}</div></div>
            <div class="full-name" style="font-size:${fontSizes.name}px;">${escapeHtml(name)}</div>
            
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'ব্যক্তিগত তথ্য' : 'Personal Information'}</div>
                <div class="info-single">
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'পিতার নাম' : "Father's Name"}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(father)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'মাতার নাম' : "Mother's Name"}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(mother)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'জন্ম তারিখ' : 'Date of Birth'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${formattedDob}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'লিঙ্গ' : 'Gender'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(gender)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'বৈবাহিক অবস্থা' : 'Marital Status'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(marital)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'জাতীয়তা' : 'Nationality'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(nationality)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'ধর্ম' : 'Religion'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(religion)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">NID:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(nid)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'মোবাইল' : 'Mobile'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(mobile)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">Email:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(email)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'বর্তমান ঠিকানা' : 'Present Address'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(presentAddr)}</div></div>
                    <div class="info-row"><div class="info-label" style="font-size:${fontSizes.label}px;">${currentLang == 'bangla' ? 'স্থায়ী ঠিকানা' : 'Permanent Address'}:</div><div class="info-value" style="font-size:${fontSizes.value}px;">${escapeHtml(permanentAddr)}</div></div>
                </div>
            </div>
            
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'শিক্ষাগত যোগ্যতা' : 'Educational Qualifications'}</div>`;
        
        for (let edu of educations) {
            let degree = currentLang == 'bangla' ? edu.degreeBn : edu.degreeEn;
            let institution = currentLang == 'bangla' ? edu.institutionBn : edu.institutionEn;
            let board = currentLang == 'bangla' ? edu.boardBn : edu.boardEn;
            html += `<div class="education-item"><div class="education-degree" style="font-size:${fontSizes.value}px;">${escapeHtml(degree)}</div>
                <div class="education-details" style="font-size:${fontSizes.text}px;">${currentLang == 'bangla' ? 'প্রতিষ্ঠান' : 'Institution'}: ${escapeHtml(institution)}</div>
                <div class="education-details" style="font-size:${fontSizes.text}px;">${currentLang == 'bangla' ? 'বোর্ড/বিশ্ববিদ্যালয়' : 'Board/University'}: ${escapeHtml(board)}</div>
                <div class="education-details" style="font-size:${fontSizes.text}px;">${currentLang == 'bangla' ? 'ফলাফল' : 'Result'}: ${escapeHtml(edu.result)} | ${currentLang == 'bangla' ? 'বছর' : 'Year'}: ${escapeHtml(edu.year)}</div></div>`;
        }
        
        html += `</div>
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'কর্মজীবনের লক্ষ্য' : 'Career Objective'}</div>
                <p style="font-size:${fontSizes.text}px;">${escapeHtml(careerObj)}</p></div>
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'কম্পিউটার দক্ষতা' : 'Computer Skills'}</div>
                <ul class="skill-list">${skills.map(s => `<li style="font-size:${fontSizes.text}px;">${escapeHtml(s)}</li>`).join('')}</ul></div>
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'ব্যক্তিগত বৈশিষ্ট্য' : 'Personal Characteristics'}</div>
                <ul class="char-list">${characteristics.map(c => `<li style="font-size:${fontSizes.text}px;">${escapeHtml(c)}</li>`).join('')}</ul></div>
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'ভাষার দক্ষতা' : 'Language Proficiency'}</div>
                <div class="language-row" style="font-size:${fontSizes.text}px;"><div class="language-name">${currentLang == 'bangla' ? 'বাংলা' : 'Bengali'}:</div><div>${escapeHtml(bengaliLevel)}</div></div>
                <div class="language-row" style="font-size:${fontSizes.text}px;"><div class="language-name">${currentLang == 'bangla' ? 'ইংরেজি' : 'English'}:</div><div>${escapeHtml(englishLevel)}</div></div></div>
            <div class="cv-section"><div class="cv-section-title" style="font-size:${fontSizes.sectionTitle}px;">${currentLang == 'bangla' ? 'রেফারেন্স' : 'Reference'}</div>`;
        
        for (let ref of references) {
            let nameRef = currentLang == 'bangla' ? ref.nameBn : ref.nameEn;
            let desig = currentLang == 'bangla' ? ref.designationBn : ref.designationEn;
            html += `<div class="ref-item" style="font-size:${fontSizes.text}px;"><strong>${escapeHtml(nameRef)}</strong><br>${currentLang == 'bangla' ? 'পদবি' : 'Designation'}: ${escapeHtml(desig)}<br>Mobile: ${escapeHtml(ref.mobile)}</div>`;
        }
        
        html += `</div><div class="declaration" style="font-size:${fontSizes.text-2}px;">${currentLang == 'bangla' ? 'আমি ঘোষণা করছি যে উপরোক্ত তথ্যগুলো সত্য ও সঠিক।' : 'I hereby declare that all the above information is true and correct.'}</div></div>`;
        document.getElementById('cvPreview').innerHTML = html;
    }
    
    function downloadPDF() {
        let element = document.getElementById('cvPreview');
        html2pdf().set({ margin: [0.5,0.5,0.5,0.5], filename: 'CV.pdf', html2canvas: { scale: 2 }, jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } }).from(element).save();
    }
    
    function printCV() { window.print(); }
    function resetAll() { if (confirm('সব তথ্য রিসেট করবেন?')) location.reload(); }
    
    // ========== EVENT LISTENERS ==========
    document.getElementById('titleSize').addEventListener('input', function() { updateFontSize('title', this.value); });
    document.getElementById('nameSize').addEventListener('input', function() { updateFontSize('name', this.value); });
    document.getElementById('sectionTitleSize').addEventListener('input', function() { updateFontSize('sectionTitle', this.value); });
    document.getElementById('labelSize').addEventListener('input', function() { updateFontSize('label', this.value); });
    document.getElementById('valueSize').addEventListener('input', function() { updateFontSize('value', this.value); });
    document.getElementById('textSize').addEventListener('input', function() { updateFontSize('text', this.value); });
    document.getElementById('resetFontBtn').addEventListener('click', resetFontSizes);
    document.getElementById('photoUpload').addEventListener('change', function() { uploadPhoto(this); });
    document.getElementById('langBanglaBtn').addEventListener('click', function() { setLanguage('bangla'); });
    document.getElementById('langEnglishBtn').addEventListener('click', function() { setLanguage('english'); });
    document.getElementById('addEducationBtn').addEventListener('click', addEducation);
    document.getElementById('addSkillBtn').addEventListener('click', addSkill);
    document.getElementById('addCharacteristicBtn').addEventListener('click', addCharacteristic);
    document.getElementById('addReferenceBtn').addEventListener('click', addReference);
    document.getElementById('downloadPdfBtn').addEventListener('click', downloadPDF);
    document.getElementById('printBtn').addEventListener('click', printCV);
    document.getElementById('resetAllBtn').addEventListener('click', resetAll);
    
    let inputs = ['nameBn','nameEn','fatherBn','fatherEn','motherBn','motherEn','dob','gender','marital','nationality','religion','nid','mobile','email','presentAddress','permanentAddress','careerBn','careerEn','bengaliLevel','englishLevel'];
    inputs.forEach(id => { let el = document.getElementById(id); if (el) el.addEventListener('input', generateCV); });
    
    renderEducation();
    renderSkills();
    renderCharacteristics();
    renderReferences();
    generateCV();
</script>
</body>
</html>