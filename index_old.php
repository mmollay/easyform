<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormWerk - Professional Form & List Generator</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <script src="assets/js/language-switcher.js" defer></script>
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
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-menu > li {
            position: relative;
        }
        
        .nav-menu a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: 6px;
        }
        
        .nav-menu a:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.08);
        }
        
        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 220px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #4a5568;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        
        .dropdown-menu a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }
        
        .dropdown-menu a i {
            width: 20px;
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
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            animation: fadeInUp 0.8s ease;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
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
                <li class="dropdown">
                    <a href="#features"><i class="star icon"></i> Features <i class="angle down icon"></i></a>
                    <div class="dropdown-menu">
                        <a href="#form-features"><i class="wpforms icon"></i> EasyForm Features</a>
                        <a href="#list-features"><i class="list icon"></i> EasyList Features</a>
                        <a href="#builders"><i class="tools icon"></i> Visual Builders</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="docs/"><i class="book icon"></i> Dokumentation <i class="angle down icon"></i></a>
                    <div class="dropdown-menu">
                        <a href="docs/easyform.html"><i class="file alternate icon"></i> EasyForm API</a>
                        <a href="docs/easylist.html"><i class="table icon"></i> EasyList API</a>
                        <a href="docs/installation.html"><i class="download icon"></i> Installation</a>
                        <a href="docs/examples.html"><i class="code icon"></i> Code-Beispiele</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#tools"><i class="tools icon"></i> Tools <i class="angle down icon"></i></a>
                    <div class="dropdown-menu">
                        <a href="form_builder.php"><i class="paint brush icon"></i> Form Builder</a>
                        <a href="list_generator.php"><i class="table icon"></i> List Generator</a>
                        <a href="beispiel_formular.php"><i class="eye icon"></i> Demo Formular</a>
                    </div>
                </li>
                <li><a href="#contact"><i class="envelope icon"></i> Kontakt</a></li>
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
        <h2 class="section-title">Warum EasyForm & EasyList?</h2>
        <p class="section-subtitle">
            Die komplette Lösung für moderne Webformulare und Datenverwaltung
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
    
    <!-- EasyList Features Section -->
    <section class="features-section" id="list-features" style="background: white;">
        <h2 class="section-title">EasyList - Datentabellen leicht gemacht</h2>
        <p class="section-subtitle">
            Erstellen Sie professionelle Datentabellen mit Sortierung, Filterung und Export-Funktionen
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="table icon"></i>
                </div>
                <h3 class="feature-title">Automatische Tabellen</h3>
                <p class="feature-description">
                    Generiert automatisch responsive Tabellen aus Arrays, Datenbanken oder APIs
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="search icon"></i>
                </div>
                <h3 class="feature-title">Intelligente Suche & Filter</h3>
                <p class="feature-description">
                    Eingebaute Suchfunktion mit spaltenspezifischen Filtern und Echtzeit-Suche
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="sort icon"></i>
                </div>
                <h3 class="feature-title">Sortierung & Pagination</h3>
                <p class="feature-description">
                    Multi-Spalten-Sortierung mit konfigurierbarer Pagination für große Datenmengen
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="download icon"></i>
                </div>
                <h3 class="feature-title">Export-Funktionen</h3>
                <p class="feature-description">
                    Export in CSV, Excel, JSON und PDF mit einem Klick
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="bolt icon"></i>
                </div>
                <h3 class="feature-title">Bulk Actions</h3>
                <p class="feature-description">
                    Massenaktionen für mehrere Zeilen gleichzeitig mit Checkbox-Auswahl
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                    <i class="mobile alternate icon"></i>
                </div>
                <h3 class="feature-title">Responsive Design</h3>
                <p class="feature-description">
                    Automatische Anpassung für mobile Geräte mit Touch-optimierten Kontrollen
                </p>
            </div>
        </div>
        
        <!-- EasyList Code Example -->
        <div style="max-width: 900px; margin: 80px auto 0; padding: 0 20px;">
            <h3 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">EasyList in Aktion</h3>
            <div style="background: #1e1e1e; border-radius: 12px; padding: 30px; overflow-x: auto;">
                <pre style="color: #e0e0e0; margin: 0;"><code><span style="color: #ce9178;">use</span> <span style="color: #4ec9b0;">EasyForm\EasyList</span>;

<span style="color: #608b4e;">// Erstelle eine Datentabelle mit allen Features</span>
<span style="color: #9cdcfe;">$list</span> = <span style="color: #c586c0;">new</span> <span style="color: #4ec9b0;">EasyList</span>(<span style="color: #ce9178;">'users'</span>, [
    <span style="color: #ce9178;">'data'</span> => <span style="color: #9cdcfe;">$users</span>,
    <span style="color: #ce9178;">'searchable'</span> => <span style="color: #569cd6;">true</span>,
    <span style="color: #ce9178;">'sortable'</span> => <span style="color: #569cd6;">true</span>,
    <span style="color: #ce9178;">'exportable'</span> => [<span style="color: #ce9178;">'csv'</span>, <span style="color: #ce9178;">'excel'</span>, <span style="color: #ce9178;">'pdf'</span>]
]);

<span style="color: #9cdcfe;">$list</span>
    -><span style="color: #dcdcaa;">column</span>(<span style="color: #ce9178;">'name'</span>, <span style="color: #ce9178;">'Name'</span>)
    -><span style="color: #dcdcaa;">column</span>(<span style="color: #ce9178;">'email'</span>, <span style="color: #ce9178;">'E-Mail'</span>)
    -><span style="color: #dcdcaa;">column</span>(<span style="color: #ce9178;">'role'</span>, <span style="color: #ce9178;">'Rolle'</span>)
    -><span style="color: #dcdcaa;">column</span>(<span style="color: #ce9178;">'created_at'</span>, <span style="color: #ce9178;">'Erstellt'</span>, [<span style="color: #ce9178;">'format'</span> => <span style="color: #ce9178;">'date'</span>])
    -><span style="color: #dcdcaa;">action</span>(<span style="color: #ce9178;">'edit'</span>, <span style="color: #ce9178;">'<i class="edit icon"></i>'</span>, [<span style="color: #ce9178;">'class'</span> => <span style="color: #ce9178;">'primary'</span>])
    -><span style="color: #dcdcaa;">action</span>(<span style="color: #ce9178;">'delete'</span>, <span style="color: #ce9178;">'<i class="trash icon"></i>'</span>, [<span style="color: #ce9178;">'class'</span> => <span style="color: #ce9178;">'red'</span>])
    -><span style="color: #dcdcaa;">bulkAction</span>(<span style="color: #ce9178;">'delete'</span>, <span style="color: #ce9178;">'Löschen'</span>)
    -><span style="color: #dcdcaa;">pagination</span>(<span style="color: #b5cea8;">20</span>)
    -><span style="color: #dcdcaa;">display</span>();</code></pre>
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
    <!-- Kontakt Section -->
    <section class="contact-section" id="contact" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 80px 0; color: white;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center; padding: 0 20px;">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Bereit durchzustarten?</h2>
            <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.95;">
                Beginnen Sie noch heute mit EasyForm & EasyList und erleben Sie, 
                wie einfach moderne Webentwicklung sein kann.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="form_builder.php" class="btn-primary" style="background: white; color: #667eea;">
                    <i class="paint brush icon"></i> Form Builder starten
                </a>
                <a href="list_generator.php" class="btn-secondary" style="background: transparent; border: 2px solid white;">
                    <i class="table icon"></i> List Generator öffnen
                </a>
                <a href="docs/" class="btn-secondary" style="background: transparent; border: 2px solid white;">
                    <i class="book icon"></i> Dokumentation lesen
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content" style="max-width: 1200px; margin: 0 auto; padding: 60px 20px 40px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <!-- Über EasyForm -->
                <div>
                    <h3 style="color: #667eea; margin-bottom: 15px; font-size: 1.3rem;">
                        <i class="wpforms icon"></i> EasyForm & EasyList
                    </h3>
                    <p style="color: #718096; line-height: 1.8; font-size: 0.95rem;">
                        Die moderne PHP-Bibliothek für professionelle Formulare und Datentabellen. 
                        Entwickelt für Entwickler, die Wert auf sauberen Code und Effizienz legen.
                    </p>
                </div>
                
                <!-- Schnellzugriff -->
                <div>
                    <h4 style="margin-bottom: 15px; color: #2d3748;">Schnellzugriff</h4>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="form_builder.php" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Form Builder
                        </a>
                        <a href="list_generator.php" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> List Generator
                        </a>
                        <a href="beispiel_formular.php" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Demo Formular
                        </a>
                        <a href="test_list_themes.html" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Theme Test
                        </a>
                    </div>
                </div>
                
                <!-- Dokumentation -->
                <div>
                    <h4 style="margin-bottom: 15px; color: #2d3748;">Dokumentation</h4>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="docs/" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Übersicht
                        </a>
                        <a href="docs/installation.html" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Installation
                        </a>
                        <a href="docs/easyform.html" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> EasyForm API
                        </a>
                        <a href="docs/easylist.html" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> EasyList API
                        </a>
                        <a href="docs/examples.html" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="angle right icon"></i> Code-Beispiele
                        </a>
                    </div>
                </div>
                
                <!-- Community -->
                <div>
                    <h4 style="margin-bottom: 15px; color: #2d3748;">Community & Support</h4>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="https://github.com/mmollay/easyform" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="github icon"></i> GitHub Repository
                        </a>
                        <a href="#" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="bug icon"></i> Fehler melden
                        </a>
                        <a href="#" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="lightbulb icon"></i> Feature vorschlagen
                        </a>
                        <a href="LICENSE" style="color: #718096; text-decoration: none; transition: color 0.3s;">
                            <i class="certificate icon"></i> MIT Lizenz
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-divider" style="border-top: 1px solid #e2e8f0; margin: 40px 0 20px;"></div>
            
            <p class="footer-copyright" style="text-align: center; color: #718096; font-size: 0.9rem;">
                © 2024 EasyForm & EasyList. Mit <i class="heart icon" style="color: #e53e3e;"></i> entwickelt für die moderne PHP-Community.
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