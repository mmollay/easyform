<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Beispiele & Demos</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <script src="../assets/js/i18n.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(120deg, #f6f8fb 0%, #e9ecf0 100%);
            min-height: 100vh;
            padding: 100px 20px 40px;
        }
        
        /* Navigation */
        .main-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 15px 30px;
        }
        
        .nav-container {
            max-width: 1200px;
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
        }
        
        .nav-menu a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-menu a:hover {
            color: #667eea;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 50px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .header-section h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
        
        .header-section p {
            color: #718096;
            font-size: 1.1rem;
        }
        
        .examples-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .example-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .example-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .example-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: white;
        }
        
        .example-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .example-description {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .example-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .tag {
            background: #f7fafc;
            color: #4a5568;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .tag.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #4a5568;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
            color: #667eea;
        }
        
        .section-title {
            font-size: 1.8rem;
            color: #2d3748;
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="../" class="nav-logo">EasyForm</a>
            <ul class="nav-menu">
                <li><a href="../" data-i18n="nav.home">Startseite</a></li>
                <li><a href="../form_builder.php" data-i18n="nav.formbuilder">Form Builder</a></li>
                <li><a href="../list_generator.php" data-i18n="nav.listgenerator">List Generator</a></li>
                <li><a href="../docs/" data-i18n="nav.docs">Dokumentation</a></li>
                <li><a href="../health-check.php" data-i18n="nav.health">Status</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <!-- Header -->
        <div class="header-section">
            <h1 data-i18n="examples.title">EasyForm Beispiele</h1>
            <p data-i18n="examples.subtitle">Entdecken Sie die Vielfalt und Leistungsfähigkeit von EasyForm anhand unserer interaktiven Demos</p>
            <a href="../" class="back-button">
                <i class="arrow left icon"></i> <span data-i18n="global.back">Zurück</span>
            </a>
        </div>
        
        <!-- Form Examples -->
        <h2 class="section-title" data-i18n="examples.forms">📝 Formular Beispiele</h2>
        <div class="examples-grid">
            <a href="01_simple_form.php" class="example-card">
                <div class="example-icon">
                    <i class="envelope icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.contact.title">Einfaches Kontaktformular</h3>
                <p class="example-description" data-i18n="examples.contact.desc">
                    Grundlegendes Kontaktformular mit den wichtigsten Feldtypen und AJAX-Submit
                </p>
                <div class="example-tags">
                    <span class="tag primary" data-i18n="examples.level.beginner">Anfänger</span>
                    <span class="tag" data-i18n="examples.tag.ajax">AJAX</span>
                    <span class="tag" data-i18n="examples.tag.validation">Validierung</span>
                </div>
            </a>
            
            <a href="02_advanced_form.php" class="example-card">
                <div class="example-icon">
                    <i class="clipboard icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.application.title">Bewerbungsformular mit Tabs</h3>
                <p class="example-description" data-i18n="examples.application.desc">
                    Komplexes Formular mit Tabs, Gruppen, bedingten Feldern und Datei-Upload
                </p>
                <div class="example-tags">
                    <span class="tag" data-i18n="examples.level.advanced">Fortgeschritten</span>
                    <span class="tag" data-i18n="examples.tag.tabs">Tabs</span>
                    <span class="tag" data-i18n="examples.tag.fileupload">File Upload</span>
                </div>
            </a>
            
            <a href="03_ajax_form.php" class="example-card">
                <div class="example-icon">
                    <i class="bolt icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.registration.title">AJAX Registrierung</h3>
                <p class="example-description" data-i18n="examples.registration.desc">
                    Dynamisches Registrierungsformular mit Live-Validierung und AJAX-Processing
                </p>
                <div class="example-tags">
                    <span class="tag" data-i18n="examples.tag.ajax">AJAX</span>
                    <span class="tag" data-i18n="examples.tag.livevalidation">Live Validation</span>
                    <span class="tag" data-i18n="examples.tag.password">Passwort</span>
                </div>
            </a>
        </div>
        
        <!-- List Examples -->
        <h2 class="section-title" data-i18n="examples.lists">📊 Listen & Tabellen</h2>
        <div class="examples-grid">
            <a href="04_list_demo.php" class="example-card">
                <div class="example-icon">
                    <i class="table icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.datalist.title">Einfache Datenliste</h3>
                <p class="example-description" data-i18n="examples.datalist.desc">
                    Grundlegende Datentabelle mit Sortierung, Suche und Pagination
                </p>
                <div class="example-tags">
                    <span class="tag primary" data-i18n="examples.level.beginner">Anfänger</span>
                    <span class="tag" data-i18n="examples.tag.sorting">Sortierung</span>
                    <span class="tag" data-i18n="examples.tag.search">Suche</span>
                </div>
            </a>
            
            <a href="05_list_advanced.php" class="example-card">
                <div class="example-icon">
                    <i class="chart bar icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.advancedlist.title">Erweiterte Datentabelle</h3>
                <p class="example-description" data-i18n="examples.advancedlist.desc">
                    Professionelle Tabelle mit Filtern, Export-Funktionen und Gruppierung
                </p>
                <div class="example-tags">
                    <span class="tag" data-i18n="examples.level.advanced">Fortgeschritten</span>
                    <span class="tag" data-i18n="examples.tag.export">Export</span>
                    <span class="tag" data-i18n="examples.tag.filter">Filter</span>
                </div>
            </a>

            <a href="06_smartform2_compat_demo.php" class="example-card">
                <div class="example-icon">
                    <i class="magic icon"></i>
                </div>
                <h3 class="example-title">Smartform2 Kompatibilität</h3>
                <p class="example-description">
                    Database-Integration, Button-System mit Conditions, Modals und vollständige Smartform2 API-Kompatibilität
                </p>
                <div class="example-tags">
                    <span class="tag primary">NEU</span>
                    <span class="tag">Buttons</span>
                    <span class="tag">Modals</span>
                    <span class="tag">Database</span>
                </div>
            </a>
        </div>
        
        <!-- Builder Tools -->
        <h2 class="section-title" data-i18n="examples.builders">🛠️ Visual Builder</h2>
        <div class="examples-grid">
            <a href="../form_builder.php" class="example-card">
                <div class="example-icon">
                    <i class="paint brush icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.formbuilder.title">Form Builder</h3>
                <p class="example-description" data-i18n="examples.formbuilder.desc">
                    Visueller Drag & Drop Form Builder für die schnelle Formularerstellung ohne Code
                </p>
                <div class="example-tags">
                    <span class="tag primary" data-i18n="examples.tag.tool">Tool</span>
                    <span class="tag" data-i18n="examples.tag.dragdrop">Drag & Drop</span>
                    <span class="tag" data-i18n="examples.tag.visual">Visual</span>
                </div>
            </a>
            
            <a href="../list_generator.php" class="example-card">
                <div class="example-icon">
                    <i class="database icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.listgen.title">List Generator</h3>
                <p class="example-description" data-i18n="examples.listgen.desc">
                    Visueller Generator für dynamische Datentabellen mit allen Features
                </p>
                <div class="example-tags">
                    <span class="tag primary" data-i18n="examples.tag.tool">Tool</span>
                    <span class="tag" data-i18n="examples.tag.generator">Generator</span>
                    <span class="tag" data-i18n="examples.tag.visual">Visual</span>
                </div>
            </a>
        </div>
        
        <!-- Resources -->
        <h2 class="section-title" data-i18n="examples.resources">📚 Weitere Ressourcen</h2>
        <div class="examples-grid">
            <a href="../docs/" class="example-card">
                <div class="example-icon">
                    <i class="book icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.docs.title">Dokumentation</h3>
                <p class="example-description" data-i18n="examples.docs.desc">
                    Ausführliche Dokumentation mit API-Referenz und Tutorials
                </p>
                <div class="example-tags">
                    <span class="tag" data-i18n="examples.tag.api">API</span>
                    <span class="tag" data-i18n="examples.tag.tutorial">Tutorial</span>
                    <span class="tag" data-i18n="examples.tag.reference">Reference</span>
                </div>
            </a>
            
            <a href="https://github.com/mmollay/easyform" class="example-card">
                <div class="example-icon">
                    <i class="github icon"></i>
                </div>
                <h3 class="example-title" data-i18n="examples.github.title">GitHub Repository</h3>
                <p class="example-description" data-i18n="examples.github.desc">
                    Source Code, Issues und Contributions auf GitHub
                </p>
                <div class="example-tags">
                    <span class="tag" data-i18n="examples.tag.opensource">Open Source</span>
                    <span class="tag" data-i18n="examples.tag.github">GitHub</span>
                    <span class="tag" data-i18n="examples.tag.license">MIT License</span>
                </div>
            </a>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
</body>
</html>