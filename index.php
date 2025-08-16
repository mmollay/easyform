<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Modern PHP Form Generator</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            overflow-x: hidden;
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
            transition: all 0.3s ease;
        }
        
        .main-nav.scrolled {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .nav-logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }
        
        .nav-menu a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .nav-menu a:hover {
            color: #667eea;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 150px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease 0.2s;
            animation-fill-mode: both;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.4s;
            animation-fill-mode: both;
        }
        
        .btn-primary, .btn-secondary {
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
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
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .feature-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .feature-description {
            color: #718096;
            line-height: 1.6;
        }
        
        /* Code Example Section */
        .code-section {
            padding: 100px 0;
            background: white;
        }
        
        .code-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        
        .code-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .code-content p {
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .code-features {
            list-style: none;
        }
        
        .code-features li {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            color: #4a5568;
        }
        
        .code-features i {
            color: #48bb78;
            font-size: 1.2rem;
        }
        
        .code-block {
            background: #1a202c;
            border-radius: 15px;
            padding: 30px;
            overflow-x: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .code-block pre {
            margin: 0;
            color: #e2e8f0;
            font-family: 'SF Mono', Monaco, 'Cascadia Code', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .code-block .comment { color: #718096; }
        .code-block .keyword { color: #ed64a6; }
        .code-block .string { color: #68d391; }
        .code-block .variable { color: #63b3ed; }
        .code-block .method { color: #fbb040; }
        
        /* Footer */
        .footer {
            background: #1a202c;
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-divider {
            width: 100px;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 30px auto;
        }
        
        .footer-copyright {
            color: #718096;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .code-container {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-logo">EasyForm</div>
            <ul class="nav-menu">
                <li><a href="#features"><i class="star icon"></i> Funktionen</a></li>
                <li><a href="examples/"><i class="code icon"></i> Beispiele</a></li>
                <li><a href="docs/"><i class="book icon"></i> Dokumentation</a></li>
                <li><a href="form_builder.php"><i class="paint brush icon"></i> Form Builder</a></li>
                <li><a href="list_generator.php"><i class="table icon"></i> List Generator</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Moderner PHP Formular Generator</h1>
            <p class="hero-subtitle">
                Erstellen Sie beeindruckende Formulare und Datenlisten in Sekunden 
                mit unserer intuitiven Fluent API und über 50 Feldtypen
            </p>
            <div class="hero-buttons">
                <a href="examples/" class="btn-primary">
                    <i class="play icon"></i> Live Demos
                </a>
                <a href="docs/" class="btn-secondary">
                    <i class="book icon"></i> Dokumentation
                </a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features-section" id="features">
        <h2 class="section-title">Warum EasyForm?</h2>
        <p class="section-subtitle">
            Alles was Sie brauchen für moderne Webformulare und Datenverwaltung
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="magic icon"></i>
                </div>
                <h3 class="feature-title">Fluent Interface</h3>
                <p class="feature-description">
                    Elegante Method Chaining API für intuitive und lesbare Formulardefinitionen
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="shield alternate icon"></i>
                </div>
                <h3 class="feature-title">Integrierte Validierung</h3>
                <p class="feature-description">
                    Client- und serverseitige Validierung mit über 20 vordefinierten Regeln
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="paint brush icon"></i>
                </div>
                <h3 class="feature-title">Visual Builder</h3>
                <p class="feature-description">
                    Drag & Drop Form Builder für schnelle visuelle Formularerstellung
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bolt icon"></i>
                </div>
                <h3 class="feature-title">AJAX Support</h3>
                <p class="feature-description">
                    Nahtlose AJAX-Integration für moderne, reaktive Benutzererfahrungen
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="table icon"></i>
                </div>
                <h3 class="feature-title">EasyList</h3>
                <p class="feature-description">
                    Leistungsstarke Datentabellen mit Sortierung, Filterung und Export
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="puzzle piece icon"></i>
                </div>
                <h3 class="feature-title">50+ Feldtypen</h3>
                <p class="feature-description">
                    Von einfachen Textfeldern bis zu komplexen Komponenten wie Datepicker
                </p>
            </div>
        </div>
    </section>
    
    <!-- Code Example Section -->
    <section class="code-section">
        <div class="code-container">
            <div class="code-content">
                <h2>So einfach geht's</h2>
                <p>
                    Mit EasyForm erstellen Sie komplexe Formulare mit nur wenigen Zeilen Code. 
                    Die intuitive API macht die Entwicklung zum Kinderspiel.
                </p>
                <ul class="code-features">
                    <li>
                        <i class="check icon"></i>
                        <span>Method Chaining für eleganten Code</span>
                    </li>
                    <li>
                        <i class="check icon"></i>
                        <span>Automatische Validierung</span>
                    </li>
                    <li>
                        <i class="check icon"></i>
                        <span>CSRF-Schutz integriert</span>
                    </li>
                    <li>
                        <i class="check icon"></i>
                        <span>Responsive Design</span>
                    </li>
                    <li>
                        <i class="check icon"></i>
                        <span>Mehrsprachige Unterstützung</span>
                    </li>
                </ul>
            </div>
            
            <div class="code-block">
                <pre><span class="comment">// Formular in 30 Sekunden erstellt!</span>
<span class="keyword">use</span> EasyForm\EasyForm;

<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'contact'</span>);

<span class="variable">$form</span>-><span class="method">heading</span>(<span class="string">'Kontaktformular'</span>)
     -><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Ihr Name'</span>, [
         <span class="string">'required'</span> => <span class="keyword">true</span>,
         <span class="string">'icon'</span> => <span class="string">'user'</span>
     ])
     -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>, [
         <span class="string">'required'</span> => <span class="keyword">true</span>,
         <span class="string">'icon'</span> => <span class="string">'mail'</span>
     ])
     -><span class="method">textarea</span>(<span class="string">'message'</span>, <span class="string">'Nachricht'</span>, [
         <span class="string">'rows'</span> => <span class="string">5</span>
     ])
     -><span class="method">ajax</span>([
         <span class="string">'url'</span> => <span class="string">'process.php'</span>,
         <span class="string">'success'</span> => <span class="string">'showSuccess'</span>
     ])
     -><span class="method">submit</span>(<span class="string">'Senden'</span>);

<span class="variable">$form</span>-><span class="method">display</span>();</pre>
            </div>
        </div>
    </section>
    
    <!-- Quick Links Section -->
    <section class="features-section">
        <h2 class="section-title">Schnellzugriff</h2>
        <p class="section-subtitle">Erkunden Sie alle Möglichkeiten von EasyForm</p>
        
        <div class="features-grid">
            <a href="examples/" style="text-decoration: none; color: inherit;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="play icon"></i>
                    </div>
                    <h3 class="feature-title">Live Beispiele</h3>
                    <p class="feature-description">
                        Interaktive Demos aller Funktionen mit vollständigem Quellcode
                    </p>
                </div>
            </a>
            
            <a href="form_builder.php" style="text-decoration: none; color: inherit;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="paint brush icon"></i>
                    </div>
                    <h3 class="feature-title">Visual Form Builder</h3>
                    <p class="feature-description">
                        Erstellen Sie Formulare per Drag & Drop ohne Programmierung
                    </p>
                </div>
            </a>
            
            <a href="list_generator.php" style="text-decoration: none; color: inherit;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="table icon"></i>
                    </div>
                    <h3 class="feature-title">List Generator</h3>
                    <p class="feature-description">
                        Generieren Sie dynamische Datentabellen mit Sortierung und Export
                    </p>
                </div>
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="docs/">Dokumentation</a>
                <a href="examples/">Beispiele</a>
                <a href="https://github.com/mmollay/easyform">GitHub</a>
                <a href="LICENSE">Lizenz</a>
            </div>
            <div class="footer-divider"></div>
            <p class="footer-copyright">
                © 2024 EasyForm. Mit ❤️ entwickelt für moderne PHP-Entwickler.
            </p>
        </div>
    </footer>
    
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script>
    $(document).ready(function() {
        // Navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.main-nav').addClass('scrolled');
            } else {
                $('.main-nav').removeClass('scrolled');
            }
        });
        
        // Smooth scrolling
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 70
                }, 800);
            }
        });
    });
    </script>
</body>
</html>