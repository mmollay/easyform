<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormWerk - Professional Form & List Generator</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <script src="assets/js/i18n.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            padding-top: 70px;
        }
        
        /* Navigation */
        .main-nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 15px 30px;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        
        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .nav-menu a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 6px;
        }
        
        .nav-menu a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.08);
        }
        
        .nav-menu a.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.12);
        }
        
        /* Hero Section - Kompakter */
        .hero-section {
            min-height: 450px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
            text-align: center;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }
        
        /* Features - Kompakter */
        .features-section {
            padding: 60px 20px;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: #2c3e50;
        }
        
        .features-grid {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .feature-description {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        /* Tools Section */
        .tools-section {
            padding: 60px 20px;
            background: white;
        }
        
        .tools-grid {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .tool-card {
            padding: 30px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .tool-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
        }
        
        .tool-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .tool-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .tool-description {
            color: #718096;
            font-size: 0.95rem;
        }
        
        /* Footer - Kompakter */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 20px 20px;
            text-align: center;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-copyright {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .nav-menu {
                display: none;
            }
            
            .language-switcher {
                top: 70px;
            }
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <button class="lang-btn active" onclick="switchLanguage('de')">
            🇩🇪 DE
        </button>
        <button class="lang-btn" onclick="switchLanguage('en')">
            🇬🇧 EN
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-logo">FormWerk</div>
            <ul class="nav-menu">
                <li><a href="#features" data-i18n="nav.features">Features</a></li>
                <li><a href="#tools" data-i18n="nav.tools">Tools</a></li>
                <li><a href="docs/" data-i18n="nav.docs">Dokumentation</a></li>
                <li><a href="examples/" data-i18n="nav.examples">Beispiele</a></li>
                <li><a href="health-check.php" data-i18n="nav.health">Status</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title" data-i18n="hero.title">Professionelle Formulare & Datentabellen</h1>
            <p class="hero-subtitle" data-i18n="hero.subtitle">
                Erstellen Sie beeindruckende Formulare und Listen ohne Code - 
                mit Drag & Drop, Live-Vorschau und automatischer Code-Generierung
            </p>
            <div class="hero-buttons">
                <a href="form_builder.php" class="btn btn-primary">
                    <i class="paint brush icon"></i>
                    <span data-i18n="hero.formBuilder">Form Builder</span>
                </a>
                <a href="list_generator.php" class="btn btn-secondary">
                    <i class="table icon"></i>
                    <span data-i18n="hero.listGenerator">List Generator</span>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features-section" id="features">
        <h2 class="section-title" data-i18n="features.title">Hauptfunktionen</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="magic icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.dragdrop">Drag & Drop</h3>
                <p class="feature-description" data-i18n="features.dragdrop.desc">
                    Intuitive visuelle Erstellung mit Drag & Drop Interface
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="code icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.codegen">Code-Generierung</h3>
                <p class="feature-description" data-i18n="features.codegen.desc">
                    Sauberer, produktionsbereiter PHP-Code auf Knopfdruck
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="mobile alternate icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.responsive">Responsive</h3>
                <p class="feature-description" data-i18n="features.responsive.desc">
                    Perfekt auf allen Geräten - Desktop, Tablet und Mobile
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="shield alternate icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.validation">Validierung</h3>
                <p class="feature-description" data-i18n="features.validation.desc">
                    Eingebaute Client- und Server-seitige Validierung
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bolt icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.ajax">AJAX Support</h3>
                <p class="feature-description" data-i18n="features.ajax.desc">
                    Nahtlose AJAX-Integration für moderne Web-Apps
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="palette icon"></i>
                </div>
                <h3 class="feature-title" data-i18n="features.themes">Themes</h3>
                <p class="feature-description" data-i18n="features.themes.desc">
                    Mehrere UI-Frameworks: Semantic UI, Bootstrap, Material
                </p>
            </div>
        </div>
    </section>
    
    <!-- Tools Section -->
    <section class="tools-section" id="tools">
        <h2 class="section-title" data-i18n="tools.title">Unsere Tools</h2>
        
        <div class="tools-grid">
            <a href="form_builder.php" class="tool-card">
                <div class="tool-icon">
                    <i class="wpforms icon"></i>
                </div>
                <h3 class="tool-title" data-i18n="tools.formbuilder">Visual Form Builder</h3>
                <p class="tool-description" data-i18n="tools.formbuilder.desc">
                    Erstellen Sie Formulare visuell mit Drag & Drop
                </p>
            </a>
            
            <a href="list_generator.php" class="tool-card">
                <div class="tool-icon">
                    <i class="table icon"></i>
                </div>
                <h3 class="tool-title" data-i18n="tools.listgen">List Generator</h3>
                <p class="tool-description" data-i18n="tools.listgen.desc">
                    Generieren Sie Datentabellen mit Sortierung und Filter
                </p>
            </a>
            
            <a href="health-check.php" class="tool-card">
                <div class="tool-icon">
                    <i class="heartbeat icon"></i>
                </div>
                <h3 class="tool-title" data-i18n="tools.health">Health Check</h3>
                <p class="tool-description" data-i18n="tools.health.desc">
                    Überprüfen Sie Ihre Installation auf Fehler
                </p>
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="docs/" data-i18n="footer.docs">Dokumentation</a>
            <a href="examples/" data-i18n="footer.examples">Beispiele</a>
            <a href="https://github.com/mmollay/easyform" data-i18n="footer.github">GitHub</a>
            <a href="LICENSE" data-i18n="footer.license">Lizenz</a>
        </div>
        <div class="footer-copyright">
            <span data-i18n="footer.copyright">© 2024 FormWerk. Alle Rechte vorbehalten.</span>
        </div>
    </footer>
    
    <script src="jquery/jquery.min.js"></script>
    <script>
        // Translations
        const translations = {
            de: {
                // Navigation
                'nav.features': 'Features',
                'nav.tools': 'Tools',
                'nav.docs': 'Dokumentation',
                'nav.examples': 'Beispiele',
                'nav.health': 'Status',
                
                // Hero
                'hero.title': 'Professionelle Formulare & Datentabellen',
                'hero.subtitle': 'Erstellen Sie beeindruckende Formulare und Listen ohne Code - mit Drag & Drop, Live-Vorschau und automatischer Code-Generierung',
                'hero.formBuilder': 'Form Builder',
                'hero.listGenerator': 'List Generator',
                
                // Features
                'features.title': 'Hauptfunktionen',
                'features.dragdrop': 'Drag & Drop',
                'features.dragdrop.desc': 'Intuitive visuelle Erstellung mit Drag & Drop Interface',
                'features.codegen': 'Code-Generierung',
                'features.codegen.desc': 'Sauberer, produktionsbereiter PHP-Code auf Knopfdruck',
                'features.responsive': 'Responsive',
                'features.responsive.desc': 'Perfekt auf allen Geräten - Desktop, Tablet und Mobile',
                'features.validation': 'Validierung',
                'features.validation.desc': 'Eingebaute Client- und Server-seitige Validierung',
                'features.ajax': 'AJAX Support',
                'features.ajax.desc': 'Nahtlose AJAX-Integration für moderne Web-Apps',
                'features.themes': 'Themes',
                'features.themes.desc': 'Mehrere UI-Frameworks: Semantic UI, Bootstrap, Material',
                
                // Tools
                'tools.title': 'Unsere Tools',
                'tools.formbuilder': 'Visual Form Builder',
                'tools.formbuilder.desc': 'Erstellen Sie Formulare visuell mit Drag & Drop',
                'tools.listgen': 'List Generator',
                'tools.listgen.desc': 'Generieren Sie Datentabellen mit Sortierung und Filter',
                'tools.health': 'Health Check',
                'tools.health.desc': 'Überprüfen Sie Ihre Installation auf Fehler',
                
                // Footer
                'footer.docs': 'Dokumentation',
                'footer.examples': 'Beispiele',
                'footer.github': 'GitHub',
                'footer.license': 'Lizenz',
                'footer.copyright': '© 2024 FormWerk. Alle Rechte vorbehalten.'
            },
            en: {
                // Navigation
                'nav.features': 'Features',
                'nav.tools': 'Tools',
                'nav.docs': 'Documentation',
                'nav.examples': 'Examples',
                'nav.health': 'Status',
                
                // Hero
                'hero.title': 'Professional Forms & Data Tables',
                'hero.subtitle': 'Create stunning forms and lists without code - with drag & drop, live preview and automatic code generation',
                'hero.formBuilder': 'Form Builder',
                'hero.listGenerator': 'List Generator',
                
                // Features
                'features.title': 'Key Features',
                'features.dragdrop': 'Drag & Drop',
                'features.dragdrop.desc': 'Intuitive visual creation with drag & drop interface',
                'features.codegen': 'Code Generation',
                'features.codegen.desc': 'Clean, production-ready PHP code at the click of a button',
                'features.responsive': 'Responsive',
                'features.responsive.desc': 'Perfect on all devices - desktop, tablet and mobile',
                'features.validation': 'Validation',
                'features.validation.desc': 'Built-in client and server-side validation',
                'features.ajax': 'AJAX Support',
                'features.ajax.desc': 'Seamless AJAX integration for modern web apps',
                'features.themes': 'Themes',
                'features.themes.desc': 'Multiple UI frameworks: Semantic UI, Bootstrap, Material',
                
                // Tools
                'tools.title': 'Our Tools',
                'tools.formbuilder': 'Visual Form Builder',
                'tools.formbuilder.desc': 'Create forms visually with drag & drop',
                'tools.listgen': 'List Generator',
                'tools.listgen.desc': 'Generate data tables with sorting and filters',
                'tools.health': 'Health Check',
                'tools.health.desc': 'Check your installation for errors',
                
                // Footer
                'footer.docs': 'Documentation',
                'footer.examples': 'Examples',
                'footer.github': 'GitHub',
                'footer.license': 'License',
                'footer.copyright': '© 2024 FormWerk. All rights reserved.'
            }
        };
        
        let currentLang = localStorage.getItem('formwerk_lang') || 'de';
        
        function switchLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('formwerk_lang', lang);
            
            // Update button states
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.lang-btn').classList.add('active');
            
            // Update texts
            document.querySelectorAll('[data-i18n]').forEach(element => {
                const key = element.getAttribute('data-i18n');
                if (translations[lang][key]) {
                    element.textContent = translations[lang][key];
                }
            });
            
            // Update HTML lang
            document.documentElement.lang = lang;
        }
        
        // Initialize language on load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial language
            if (currentLang === 'en') {
                document.querySelector('.lang-btn:nth-child(2)').click();
            }
        });
    </script>
</body>
</html>