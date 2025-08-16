<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Dokumentation</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7f9fc;
            color: #2d3748;
            line-height: 1.6;
        }
        
        /* Navigation */
        .nav-header {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            text-decoration: none;
        }
        
        .nav-brand i {
            color: #667eea;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            color: #4a5568;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav-links a:hover {
            background: #f0f4f8;
            color: #667eea;
        }
        
        .nav-links a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        /* Layout */
        .main-layout {
            display: flex;
            margin-top: 60px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            height: calc(100vh - 60px);
            position: fixed;
            overflow-y: auto;
            border-right: 1px solid #e2e8f0;
            padding: 20px;
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        
        .sidebar-link {
            display: block;
            padding: 8px 12px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        
        .sidebar-link:hover {
            background: #f7fafc;
            color: #667eea;
            padding-left: 16px;
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
            color: #667eea;
            border-left: 3px solid #667eea;
        }
        
        /* Content */
        .content {
            margin-left: 280px;
            padding: 40px;
            flex: 1;
        }
        
        .content-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .content-header h1 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .content-header p {
            color: #718096;
            font-size: 1.1rem;
        }
        
        .doc-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .doc-section h2 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .doc-section h3 {
            font-size: 1.2rem;
            color: #2d3748;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .doc-section h4 {
            font-size: 1rem;
            color: #4a5568;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        /* Code blocks */
        .code-block {
            background: #1a202c;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .code-block .comment {
            color: #718096;
        }
        
        .code-block .keyword {
            color: #ed64a6;
        }
        
        .code-block .string {
            color: #68d391;
        }
        
        .code-block .variable {
            color: #63b3ed;
        }
        
        .code-block .method {
            color: #fbb040;
        }
        
        /* Inline code */
        code {
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.9em;
            color: #667eea;
        }
        
        /* Tables */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .doc-table th {
            background: #f7fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .doc-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .doc-table tr:hover {
            background: #f7fafc;
        }
        
        /* Alert boxes */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            align-items: start;
            gap: 12px;
        }
        
        .alert i {
            font-size: 1.2rem;
            margin-top: 2px;
        }
        
        .alert-info {
            background: #ebf8ff;
            border-left: 4px solid #4299e1;
            color: #2b6cb1;
        }
        
        .alert-info i {
            color: #4299e1;
        }
        
        .alert-success {
            background: #f0fdf4;
            border-left: 4px solid #48bb78;
            color: #22543d;
        }
        
        .alert-success i {
            color: #48bb78;
        }
        
        .alert-warning {
            background: #fffbeb;
            border-left: 4px solid #f6ad55;
            color: #7c4e08;
        }
        
        .alert-warning i {
            color: #f6ad55;
        }
        
        /* Feature cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .feature-card {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        
        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .feature-card h4 {
            color: #2d3748;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .feature-card h4 i {
            color: #667eea;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .content {
                margin-left: 0;
            }
        }
        
        /* TOC */
        .toc {
            background: #f7fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .toc h4 {
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        .toc ul {
            list-style: none;
            padding-left: 0;
        }
        
        .toc ul ul {
            padding-left: 20px;
            margin-top: 5px;
        }
        
        .toc a {
            color: #4a5568;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: color 0.2s;
        }
        
        .toc a:hover {
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-header">
        <div class="nav-container">
            <a href="../landing.php" class="nav-brand">
                <i class="book icon"></i>
                EasyForm Docs
            </a>
            <div class="nav-links">
                <a href="form_builder.php">
                    <i class="magic icon"></i> Form Builder
                </a>
                <a href="list_generator.php">
                    <i class="list icon"></i> List Generator
                </a>
                <a href="docs_list.php">
                    <i class="book icon"></i> EasyList Docs
                </a>
                <a href="examples/index.php">
                    <i class="eye icon"></i> Beispiele
                </a>
                <a href="../landing.php">
                    <i class="home icon"></i> Home
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">Erste Schritte</div>
                <a href="#installation" class="sidebar-link active">Installation</a>
                <a href="#quick-start" class="sidebar-link">Quick Start</a>
                <a href="#basic-usage" class="sidebar-link">Grundlagen</a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Form Builder</div>
                <a href="#builder-intro" class="sidebar-link">Einführung</a>
                <a href="#builder-components" class="sidebar-link">Komponenten</a>
                <a href="#builder-grid" class="sidebar-link">Grid System</a>
                <a href="#builder-export" class="sidebar-link">Code Export</a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">PHP API</div>
                <a href="#api-basics" class="sidebar-link">Basics</a>
                <a href="#api-fields" class="sidebar-link">Feldtypen</a>
                <a href="#api-validation" class="sidebar-link">Validierung</a>
                <a href="#api-ajax" class="sidebar-link">AJAX</a>
                <a href="#api-methods" class="sidebar-link">Methoden</a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Erweitert</div>
                <a href="#advanced-layouts" class="sidebar-link">Layouts</a>
                <a href="#advanced-styling" class="sidebar-link">Styling</a>
                <a href="#advanced-events" class="sidebar-link">Events</a>
                <a href="#advanced-custom" class="sidebar-link">Custom Fields</a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">EasyList</div>
                <a href="#list-intro" class="sidebar-link">Einführung</a>
                <a href="#list-features" class="sidebar-link">Features</a>
                <a href="#list-generator" class="sidebar-link">List Generator</a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Referenz</div>
                <a href="#ref-options" class="sidebar-link">Optionen</a>
                <a href="#ref-rules" class="sidebar-link">Validierungsregeln</a>
                <a href="#ref-icons" class="sidebar-link">Icons</a>
                <a href="#ref-examples" class="sidebar-link">Beispiele</a>
            </div>
        </aside>
        
        <!-- Content -->
        <main class="content">
            <div class="content-header">
                <h1>EasyForm Dokumentation</h1>
                <p>Vollständige Anleitung für den modernen PHP Form Generator mit Drag & Drop Builder</p>
            </div>
            
            <!-- Installation -->
            <section class="doc-section" id="installation">
                <h2>Installation</h2>
                
                <div class="alert alert-info">
                    <i class="info circle icon"></i>
                    <div>
                        <strong>Systemanforderungen:</strong> PHP 7.4+, jQuery 3.x, Semantic UI 2.x
                    </div>
                </div>
                
                <h3>1. Download & Setup</h3>
                <p>Laden Sie EasyForm herunter und kopieren Sie den <code>easy_form</code> Ordner in Ihr Projekt:</p>
                
                <div class="code-block">
<pre><span class="comment"># Projekt-Struktur</span>
/your-project
├── /easy_form
│   ├── EasyForm.php
│   ├── form_builder.php
│   ├── autoload.php
│   └── /examples
├── /semantic
│   └── /dist
└── /jquery</pre>
                </div>
                
                <h3>2. Abhängigkeiten einbinden</h3>
                <p>Fügen Sie jQuery und Semantic UI in Ihre HTML-Seite ein:</p>
                
                <div class="code-block">
<pre><span class="comment">&lt;!-- CSS --&gt;</span>
&lt;link rel="stylesheet" href="semantic/dist/semantic.min.css"&gt;

<span class="comment">&lt;!-- JavaScript --&gt;</span>
&lt;script src="jquery/jquery.min.js"&gt;&lt;/script&gt;
&lt;script src="semantic/dist/semantic.min.js"&gt;&lt;/script&gt;</pre>
                </div>
                
                <h3>3. Autoloader einbinden</h3>
                <div class="code-block">
<pre><span class="keyword">require_once</span> <span class="string">'easy_form/autoload.php'</span>;
<span class="keyword">use</span> EasyForm\EasyForm;</pre>
                </div>
            </section>
            
            <!-- Quick Start -->
            <section class="doc-section" id="quick-start">
                <h2>Quick Start</h2>
                
                <h3>Option 1: Form Builder (Visual)</h3>
                <p>Der einfachste Weg, ein Formular zu erstellen:</p>
                
                <ol>
                    <li>Öffnen Sie <code>/easy_form/form_builder.php</code></li>
                    <li>Ziehen Sie Komponenten aus der linken Sidebar</li>
                    <li>Konfigurieren Sie die Felder per Klick</li>
                    <li>Kopieren Sie den generierten Code</li>
                </ol>
                
                <div class="alert alert-success">
                    <i class="magic icon"></i>
                    <div>
                        <strong>Tipp:</strong> Der Form Builder generiert automatisch sauberen PHP-Code, den Sie direkt in Ihre Anwendung einbauen können.
                    </div>
                </div>
                
                <h3>Option 2: PHP Code (Programmatisch)</h3>
                <div class="code-block">
<pre><span class="comment">// Formular erstellen</span>
<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'contact_form'</span>);

<span class="comment">// Felder hinzufügen</span>
<span class="variable">$form</span>-><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Ihr Name'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
     -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
     -><span class="method">textarea</span>(<span class="string">'message'</span>, <span class="string">'Nachricht'</span>)
     -><span class="method">submit</span>(<span class="string">'Senden'</span>);

<span class="comment">// Formular anzeigen</span>
<span class="variable">$form</span>-><span class="method">display</span>();</pre>
                </div>
            </section>
            
            <!-- Form Builder -->
            <section class="doc-section" id="builder-intro">
                <h2>Form Builder</h2>
                
                <p>Der visuelle Form Builder ermöglicht es, Formulare per Drag & Drop zu erstellen:</p>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <h4><i class="mouse pointer icon"></i> Drag & Drop</h4>
                        <p>Ziehen Sie Komponenten einfach in den Builder-Bereich</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="edit icon"></i> Live-Konfiguration</h4>
                        <p>Bearbeiten Sie Eigenschaften direkt im Builder</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="code icon"></i> Code-Generierung</h4>
                        <p>Automatische Erstellung von PHP, HTML und JSON</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="eye icon"></i> Live-Vorschau</h4>
                        <p>Sehen Sie das fertige Formular in Echtzeit</p>
                    </div>
                </div>
                
                <h3 id="builder-components">Verfügbare Komponenten</h3>
                
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Komponente</th>
                            <th>Beschreibung</th>
                            <th>Icon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>text</code></td>
                            <td>Standard Textfeld</td>
                            <td><i class="font icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>email</code></td>
                            <td>E-Mail Eingabefeld</td>
                            <td><i class="mail icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>password</code></td>
                            <td>Passwort-Feld</td>
                            <td><i class="lock icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>textarea</code></td>
                            <td>Mehrzeiliges Textfeld</td>
                            <td><i class="align left icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>select</code></td>
                            <td>Dropdown-Auswahl</td>
                            <td><i class="dropdown icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>checkbox</code></td>
                            <td>Checkbox</td>
                            <td><i class="check square icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>radio</code></td>
                            <td>Radio Buttons</td>
                            <td><i class="dot circle icon"></i></td>
                        </tr>
                        <tr>
                            <td><code>grid</code></td>
                            <td>Grid Layout System</td>
                            <td><i class="grid layout icon"></i></td>
                        </tr>
                    </tbody>
                </table>
                
                <h3 id="builder-grid">Grid System</h3>
                <p>Das Grid System ermöglicht komplexe Layouts:</p>
                
                <div class="code-block">
<pre><span class="comment">// 3-Spalten Grid</span>
Grid Row (3 Spalten)
├── Spalte 1 (33%)
├── Spalte 2 (33%)
└── Spalte 3 (33%)

<span class="comment">// Custom Grid</span>
Grid Row (Custom)
├── Spalte 1 (50% - 8/16)
├── Spalte 2 (25% - 4/16)
└── Spalte 3 (25% - 4/16)</pre>
                </div>
                
                <div class="alert alert-info">
                    <i class="info icon"></i>
                    <div>
                        <strong>Verschachtelte Grids:</strong> Sie können Grids innerhalb von Grid-Spalten platzieren für komplexe Layouts.
                    </div>
                </div>
            </section>
            
            <!-- PHP API -->
            <section class="doc-section" id="api-basics">
                <h2>PHP API Grundlagen</h2>
                
                <h3>Formular erstellen</h3>
                <div class="code-block">
<pre><span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'form_id'</span>, [
    <span class="string">'width'</span> => <span class="string">600</span>,
    <span class="string">'size'</span> => <span class="string">'large'</span>,
    <span class="string">'language'</span> => <span class="string">'de'</span>,
    <span class="string">'liveValidation'</span> => <span class="keyword">true</span>
]);</pre>
                </div>
                
                <h3>Fluent Interface</h3>
                <p>Alle Methoden können verkettet werden:</p>
                
                <div class="code-block">
<pre><span class="variable">$form</span>
    -><span class="method">action</span>(<span class="string">'process.php'</span>)
    -><span class="method">method</span>(<span class="string">'POST'</span>)
    -><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>)
    -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>)
    -><span class="method">submit</span>(<span class="string">'Senden'</span>);</pre>
                </div>
                
                <h3 id="api-fields">Feldtypen</h3>
                
                <h4>Text-Eingabefelder</h4>
                <div class="code-block">
<pre><span class="comment">// Standard Textfeld</span>
<span class="variable">$form</span>-><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>, [
    <span class="string">'placeholder'</span> => <span class="string">'Ihr Name'</span>,
    <span class="string">'required'</span> => <span class="keyword">true</span>,
    <span class="string">'icon'</span> => <span class="string">'user'</span>,
    <span class="string">'help'</span> => <span class="string">'Bitte geben Sie Ihren Namen ein'</span>
]);

<span class="comment">// E-Mail Feld</span>
<span class="variable">$form</span>-><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>);

<span class="comment">// Passwort</span>
<span class="variable">$form</span>-><span class="method">password</span>(<span class="string">'password'</span>, <span class="string">'Passwort'</span>);

<span class="comment">// Telefon</span>
<span class="variable">$form</span>-><span class="method">tel</span>(<span class="string">'phone'</span>, <span class="string">'Telefon'</span>);

<span class="comment">// URL</span>
<span class="variable">$form</span>-><span class="method">url</span>(<span class="string">'website'</span>, <span class="string">'Website'</span>);

<span class="comment">// Zahl</span>
<span class="variable">$form</span>-><span class="method">number</span>(<span class="string">'age'</span>, <span class="string">'Alter'</span>, [
    <span class="string">'min'</span> => <span class="string">18</span>,
    <span class="string">'max'</span> => <span class="string">120</span>
]);

<span class="comment">// Datum</span>
<span class="variable">$form</span>-><span class="method">date</span>(<span class="string">'birthday'</span>, <span class="string">'Geburtstag'</span>);</pre>
                </div>
                
                <h4>Auswahl-Felder</h4>
                <div class="code-block">
<pre><span class="comment">// Dropdown</span>
<span class="variable">$form</span>-><span class="method">select</span>(<span class="string">'country'</span>, <span class="string">'Land'</span>, [
    <span class="string">'de'</span> => <span class="string">'Deutschland'</span>,
    <span class="string">'at'</span> => <span class="string">'Österreich'</span>,
    <span class="string">'ch'</span> => <span class="string">'Schweiz'</span>
], [<span class="string">'value'</span> => <span class="string">'de'</span>]);

<span class="comment">// Radio Buttons</span>
<span class="variable">$form</span>-><span class="method">radio</span>(<span class="string">'gender'</span>, <span class="string">'Geschlecht'</span>, [
    <span class="string">'m'</span> => <span class="string">'Männlich'</span>,
    <span class="string">'f'</span> => <span class="string">'Weiblich'</span>,
    <span class="string">'d'</span> => <span class="string">'Divers'</span>
], [<span class="string">'inline'</span> => <span class="keyword">true</span>]);

<span class="comment">// Checkbox</span>
<span class="variable">$form</span>-><span class="method">checkbox</span>(<span class="string">'newsletter'</span>, <span class="string">'Newsletter abonnieren'</span>, [
    <span class="string">'checked'</span> => <span class="keyword">true</span>,
    <span class="string">'toggle'</span> => <span class="keyword">true</span>
]);</pre>
                </div>
                
                <h4>Weitere Elemente</h4>
                <div class="code-block">
<pre><span class="comment">// Textarea</span>
<span class="variable">$form</span>-><span class="method">textarea</span>(<span class="string">'message'</span>, <span class="string">'Nachricht'</span>, [
    <span class="string">'rows'</span> => <span class="string">5</span>,
    <span class="string">'placeholder'</span> => <span class="string">'Ihre Nachricht...'</span>
]);

<span class="comment">// Range Slider</span>
<span class="variable">$form</span>-><span class="method">range</span>(<span class="string">'volume'</span>, <span class="string">'Lautstärke'</span>, [
    <span class="string">'min'</span> => <span class="string">0</span>,
    <span class="string">'max'</span> => <span class="string">100</span>,
    <span class="string">'value'</span> => <span class="string">50</span>
]);

<span class="comment">// Überschrift</span>
<span class="variable">$form</span>-><span class="method">heading</span>(<span class="string">'Kontaktdaten'</span>, <span class="string">2</span>);

<span class="comment">// Trennlinie</span>
<span class="variable">$form</span>-><span class="method">divider</span>();

<span class="comment">// HTML Content</span>
<span class="variable">$form</span>-><span class="method">html</span>(<span class="string">'&lt;p&gt;Custom HTML&lt;/p&gt;'</span>);

<span class="comment">// Buttons</span>
<span class="variable">$form</span>-><span class="method">submit</span>(<span class="string">'Senden'</span>, [<span class="string">'icon'</span> => <span class="string">'send'</span>]);
<span class="variable">$form</span>-><span class="method">reset</span>(<span class="string">'Zurücksetzen'</span>);</pre>
                </div>
            </section>
            
            <!-- Validation -->
            <section class="doc-section" id="api-validation">
                <h2>Validierung</h2>
                
                <h3>Client-seitige Validierung</h3>
                <div class="code-block">
<pre><span class="comment">// Validierungsregeln definieren</span>
<span class="variable">$form</span>-><span class="method">rule</span>(<span class="string">'email'</span>, <span class="string">'required|email'</span>)
     -><span class="method">rule</span>(<span class="string">'password'</span>, <span class="string">'required|minlength[8]'</span>)
     -><span class="method">rule</span>(<span class="string">'age'</span>, <span class="string">'number|min[18]|max[120]'</span>);</pre>
                </div>
                
                <h3 id="ref-rules">Verfügbare Validierungsregeln</h3>
                
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Regel</th>
                            <th>Beschreibung</th>
                            <th>Beispiel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>required</code></td>
                            <td>Pflichtfeld</td>
                            <td><code>required</code></td>
                        </tr>
                        <tr>
                            <td><code>email</code></td>
                            <td>Gültige E-Mail-Adresse</td>
                            <td><code>email</code></td>
                        </tr>
                        <tr>
                            <td><code>url</code></td>
                            <td>Gültige URL</td>
                            <td><code>url</code></td>
                        </tr>
                        <tr>
                            <td><code>number</code></td>
                            <td>Nur Zahlen</td>
                            <td><code>number</code></td>
                        </tr>
                        <tr>
                            <td><code>alpha</code></td>
                            <td>Nur Buchstaben</td>
                            <td><code>alpha</code></td>
                        </tr>
                        <tr>
                            <td><code>alphanumeric</code></td>
                            <td>Buchstaben und Zahlen</td>
                            <td><code>alphanumeric</code></td>
                        </tr>
                        <tr>
                            <td><code>minlength[n]</code></td>
                            <td>Mindestlänge</td>
                            <td><code>minlength[8]</code></td>
                        </tr>
                        <tr>
                            <td><code>maxlength[n]</code></td>
                            <td>Maximallänge</td>
                            <td><code>maxlength[50]</code></td>
                        </tr>
                        <tr>
                            <td><code>min[n]</code></td>
                            <td>Mindestwert</td>
                            <td><code>min[18]</code></td>
                        </tr>
                        <tr>
                            <td><code>max[n]</code></td>
                            <td>Maximalwert</td>
                            <td><code>max[100]</code></td>
                        </tr>
                        <tr>
                            <td><code>match[field]</code></td>
                            <td>Muss mit anderem Feld übereinstimmen</td>
                            <td><code>match[password]</code></td>
                        </tr>
                        <tr>
                            <td><code>pattern[regex]</code></td>
                            <td>Regex-Pattern</td>
                            <td><code>pattern[^[A-Z]{2}[0-9]{4}$]</code></td>
                        </tr>
                        <tr>
                            <td><code>phone</code></td>
                            <td>Telefonnummer</td>
                            <td><code>phone</code></td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Custom Fehlermeldungen</h3>
                <div class="code-block">
<pre><span class="variable">$form</span>-><span class="method">messages</span>([
    <span class="string">'email.required'</span> => <span class="string">'Bitte geben Sie Ihre E-Mail-Adresse ein'</span>,
    <span class="string">'email.email'</span> => <span class="string">'Die E-Mail-Adresse ist ungültig'</span>,
    <span class="string">'password.minlength'</span> => <span class="string">'Das Passwort muss mindestens 8 Zeichen lang sein'</span>
]);</pre>
                </div>
            </section>
            
            <!-- AJAX -->
            <section class="doc-section" id="api-ajax">
                <h2>AJAX Integration</h2>
                
                <h3>AJAX-Formular konfigurieren</h3>
                <div class="code-block">
<pre><span class="variable">$form</span>-><span class="method">ajax</span>([
    <span class="string">'success'</span> => <span class="string">'function(response) {
        if(response.success) {
            $("#result").html("&lt;div class=\"ui success message\"&gt;" + response.message + "&lt;/div&gt;");
            $("#form_id")[0].reset();
        }
    }'</span>,
    <span class="string">'error'</span> => <span class="string">'function(xhr) {
        $("#result").html("&lt;div class=\"ui error message\"&gt;Fehler beim Senden!&lt;/div&gt;");
    }'</span>,
    <span class="string">'beforeSend'</span> => <span class="string">'$("#submit_btn").addClass("loading")'</span>,
    <span class="string">'complete'</span> => <span class="string">'$("#submit_btn").removeClass("loading")'</span>
]);</pre>
                </div>
                
                <h3>Server-Handler (process.php)</h3>
                <div class="code-block">
<pre><span class="keyword">header</span>(<span class="string">'Content-Type: application/json'</span>);
<span class="keyword">session_start</span>();

<span class="comment">// CSRF Token prüfen</span>
<span class="keyword">if</span> (<span class="variable">$_POST</span>[<span class="string">'csrf_token'</span>] !== <span class="variable">$_SESSION</span>[<span class="string">'csrf_token'</span>]) {
    <span class="keyword">die</span>(<span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">false</span>,
        <span class="string">'message'</span> => <span class="string">'Invalid token'</span>
    ]));
}

<span class="comment">// Formulardaten verarbeiten</span>
<span class="variable">$data</span> = <span class="variable">$_POST</span>;

<span class="comment">// Validierung...</span>

<span class="comment">// Response</span>
<span class="keyword">echo</span> <span class="method">json_encode</span>([
    <span class="string">'success'</span> => <span class="keyword">true</span>,
    <span class="string">'message'</span> => <span class="string">'Formular erfolgreich gesendet!'</span>,
    <span class="string">'data'</span> => <span class="variable">$data</span>
]);</pre>
                </div>
            </section>
            
            <!-- Advanced -->
            <section class="doc-section" id="advanced-layouts">
                <h2>Erweiterte Layouts</h2>
                
                <h3>Grid Layout</h3>
                <div class="code-block">
<pre><span class="comment">// 2-Spalten Layout</span>
<span class="variable">$form</span>-><span class="method">startGrid</span>(<span class="string">2</span>)
     -><span class="method">text</span>(<span class="string">'firstname'</span>, <span class="string">'Vorname'</span>)
     -><span class="method">text</span>(<span class="string">'lastname'</span>, <span class="string">'Nachname'</span>)
     -><span class="method">endGrid</span>();

<span class="comment">// Custom Grid</span>
<span class="variable">$form</span>-><span class="method">startGrid</span>([<span class="string">8</span>, <span class="string">4</span>, <span class="string">4</span>]) <span class="comment">// 50%, 25%, 25%</span>
     -><span class="method">text</span>(<span class="string">'street'</span>, <span class="string">'Straße'</span>)
     -><span class="method">text</span>(<span class="string">'zip'</span>, <span class="string">'PLZ'</span>)
     -><span class="method">text</span>(<span class="string">'city'</span>, <span class="string">'Stadt'</span>)
     -><span class="method">endGrid</span>();</pre>
                </div>
                
                <h3>Gruppen</h3>
                <div class="code-block">
<pre><span class="variable">$form</span>-><span class="method">startGroup</span>(<span class="string">'Persönliche Daten'</span>)
     -><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>)
     -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>)
     -><span class="method">endGroup</span>()
     
     -><span class="method">startGroup</span>(<span class="string">'Adresse'</span>)
     -><span class="method">text</span>(<span class="string">'street'</span>, <span class="string">'Straße'</span>)
     -><span class="method">text</span>(<span class="string">'city'</span>, <span class="string">'Stadt'</span>)
     -><span class="method">endGroup</span>();</pre>
                </div>
                
                <h3>Tabs</h3>
                <div class="code-block">
<pre><span class="variable">$form</span>-><span class="method">startTabs</span>()
     -><span class="method">tab</span>(<span class="string">'Allgemein'</span>)
     -><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>)
     -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>)
     
     -><span class="method">tab</span>(<span class="string">'Erweitert'</span>)
     -><span class="method">text</span>(<span class="string">'company'</span>, <span class="string">'Firma'</span>)
     -><span class="method">text</span>(<span class="string">'website'</span>, <span class="string">'Website'</span>)
     
     -><span class="method">endTabs</span>();</pre>
                </div>
            </section>
            
            <!-- Options Reference -->
            <section class="doc-section" id="ref-options">
                <h2>Optionen-Referenz</h2>
                
                <h3>Formular-Optionen</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Typ</th>
                            <th>Standard</th>
                            <th>Beschreibung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>width</code></td>
                            <td>integer</td>
                            <td>auto</td>
                            <td>Breite in Pixeln</td>
                        </tr>
                        <tr>
                            <td><code>size</code></td>
                            <td>string</td>
                            <td>medium</td>
                            <td>tiny, small, medium, large, huge</td>
                        </tr>
                        <tr>
                            <td><code>class</code></td>
                            <td>string</td>
                            <td>-</td>
                            <td>Zusätzliche CSS-Klasse</td>
                        </tr>
                        <tr>
                            <td><code>language</code></td>
                            <td>string</td>
                            <td>de</td>
                            <td>Sprache für Meldungen</td>
                        </tr>
                        <tr>
                            <td><code>liveValidation</code></td>
                            <td>boolean</td>
                            <td>false</td>
                            <td>Live-Validierung aktivieren</td>
                        </tr>
                        <tr>
                            <td><code>ajax</code></td>
                            <td>array</td>
                            <td>-</td>
                            <td>AJAX-Konfiguration</td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Feld-Optionen</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Typ</th>
                            <th>Beschreibung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>required</code></td>
                            <td>boolean</td>
                            <td>Pflichtfeld</td>
                        </tr>
                        <tr>
                            <td><code>placeholder</code></td>
                            <td>string</td>
                            <td>Platzhalter-Text</td>
                        </tr>
                        <tr>
                            <td><code>icon</code></td>
                            <td>string</td>
                            <td>Semantic UI Icon</td>
                        </tr>
                        <tr>
                            <td><code>help</code></td>
                            <td>string</td>
                            <td>Hilfetext unter dem Feld</td>
                        </tr>
                        <tr>
                            <td><code>value</code></td>
                            <td>mixed</td>
                            <td>Standardwert</td>
                        </tr>
                        <tr>
                            <td><code>disabled</code></td>
                            <td>boolean</td>
                            <td>Feld deaktivieren</td>
                        </tr>
                        <tr>
                            <td><code>readonly</code></td>
                            <td>boolean</td>
                            <td>Nur lesen</td>
                        </tr>
                        <tr>
                            <td><code>class</code></td>
                            <td>string</td>
                            <td>Zusätzliche CSS-Klasse</td>
                        </tr>
                        <tr>
                            <td><code>rules</code></td>
                            <td>array</td>
                            <td>Validierungsregeln</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            
            <!-- EasyList -->
            <section class="doc-section" id="list-intro">
                <h2>EasyList - Datenlisten Generator</h2>
                
                <p>EasyList ist ein leistungsstarkes Tool zum Erstellen interaktiver Datenlisten mit minimalem Code:</p>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <h4><i class="search icon"></i> Suche & Filter</h4>
                        <p>Integrierte Such- und Filterfunktionen</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="sort icon"></i> Sortierung</h4>
                        <p>Klickbare Spaltenköpfe zum Sortieren</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="numbered list icon"></i> Pagination</h4>
                        <p>Automatische Seitennummerierung</p>
                    </div>
                    <div class="feature-card">
                        <h4><i class="download icon"></i> Export</h4>
                        <p>CSV, Excel und JSON Export</p>
                    </div>
                </div>
                
                <h3>Quick Start</h3>
                <div class="code-block">
<pre><span class="keyword">use</span> EasyForm\EasyList;

<span class="variable">$list</span> = <span class="keyword">new</span> <span class="method">EasyList</span>(<span class="string">'users'</span>);
<span class="variable">$list</span>-><span class="method">data</span>(<span class="variable">$users</span>)
     -><span class="method">column</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>)
     -><span class="method">column</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>)
     -><span class="method">column</span>(<span class="string">'status'</span>, <span class="string">'Status'</span>)
     -><span class="method">searchable</span>(<span class="keyword">true</span>)
     -><span class="method">sortable</span>(<span class="keyword">true</span>)
     -><span class="method">display</span>();</pre>
                </div>
            </section>
            
            <section class="doc-section" id="list-features">
                <h2>EasyList Features</h2>
                
                <h3>Spalten mit erweiterten Optionen</h3>
                <div class="code-block">
<pre><span class="variable">$list</span>-><span class="method">column</span>(<span class="string">'price'</span>, <span class="string">'Preis'</span>, [
    <span class="string">'type'</span> => <span class="string">'number'</span>,
    <span class="string">'align'</span> => <span class="string">'right'</span>,
    <span class="string">'format'</span> => <span class="keyword">function</span>(<span class="variable">$value</span>) {
        <span class="keyword">return</span> <span class="string">'€ '</span> . <span class="method">number_format</span>(<span class="variable">$value</span>, <span class="string">2</span>, <span class="string">','</span>, <span class="string">'.'</span>);
    },
    <span class="string">'filter'</span> => [
        <span class="string">'type'</span> => <span class="string">'range'</span>,
        <span class="string">'min'</span> => <span class="string">0</span>,
        <span class="string">'max'</span> => <span class="string">1000</span>
    ]
]);</pre>
                </div>
                
                <h3>Bulk Actions</h3>
                <div class="code-block">
<pre><span class="variable">$list</span>-><span class="method">selectable</span>(<span class="keyword">true</span>)
     -><span class="method">bulkActions</span>([
         <span class="string">'delete'</span> => [<span class="string">'label'</span> => <span class="string">'Löschen'</span>, <span class="string">'icon'</span> => <span class="string">'trash'</span>],
         <span class="string">'export'</span> => [<span class="string">'label'</span> => <span class="string">'Exportieren'</span>, <span class="string">'icon'</span> => <span class="string">'download'</span>]
     ]);</pre>
                </div>
                
                <h3>AJAX Data Loading</h3>
                <div class="code-block">
<pre><span class="variable">$list</span>-><span class="method">ajax</span>(<span class="string">'api/users.json'</span>)
     -><span class="method">column</span>(<span class="string">'id'</span>, <span class="string">'ID'</span>)
     -><span class="method">column</span>(<span class="string">'name'</span>, <span class="string">'Name'</span>)
     -><span class="method">display</span>();</pre>
                </div>
            </section>
            
            <section class="doc-section" id="list-generator">
                <h2>List Generator</h2>
                
                <p>Der visuelle List Generator ermöglicht es, Listen per Drag & Drop zu erstellen:</p>
                
                <ol>
                    <li>Öffnen Sie <code>/easy_form/list_generator.php</code></li>
                    <li>Konfigurieren Sie Spalten und Features</li>
                    <li>Testen Sie die Live-Vorschau</li>
                    <li>Kopieren Sie den generierten Code</li>
                </ol>
                
                <div class="alert alert-info">
                    <i class="info circle icon"></i>
                    <div>
                        <strong>Tipp:</strong> Der List Generator erstellt automatisch PHP-Code mit allen konfigurierten Features.
                        Besuchen Sie die <a href="docs_list.php">vollständige EasyList Dokumentation</a> für weitere Details.
                    </div>
                </div>
            </section>
            
            <!-- Examples -->
            <section class="doc-section" id="ref-examples">
                <h2>Vollständige Beispiele</h2>
                
                <h3>Kontaktformular mit AJAX</h3>
                <div class="code-block">
<pre><span class="keyword">use</span> EasyForm\EasyForm;

<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'contact'</span>, [
    <span class="string">'width'</span> => <span class="string">600</span>,
    <span class="string">'liveValidation'</span> => <span class="keyword">true</span>
]);

<span class="variable">$form</span>
    -><span class="method">action</span>(<span class="string">'process.php'</span>)
    -><span class="method">ajax</span>([
        <span class="string">'success'</span> => <span class="string">'handleSuccess'</span>,
        <span class="string">'error'</span> => <span class="string">'handleError'</span>
    ])
    
    -><span class="method">heading</span>(<span class="string">'Kontaktformular'</span>, <span class="string">2</span>)
    
    -><span class="method">startGrid</span>(<span class="string">2</span>)
    -><span class="method">text</span>(<span class="string">'firstname'</span>, <span class="string">'Vorname'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'icon'</span> => <span class="string">'user'</span>
    ])
    -><span class="method">text</span>(<span class="string">'lastname'</span>, <span class="string">'Nachname'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'icon'</span> => <span class="string">'user'</span>
    ])
    -><span class="method">endGrid</span>()
    
    -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail-Adresse'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'icon'</span> => <span class="string">'mail'</span>,
        <span class="string">'placeholder'</span> => <span class="string">'ihre@email.de'</span>
    ])
    
    -><span class="method">select</span>(<span class="string">'subject'</span>, <span class="string">'Betreff'</span>, [
        <span class="string">'general'</span> => <span class="string">'Allgemeine Anfrage'</span>,
        <span class="string">'support'</span> => <span class="string">'Support'</span>,
        <span class="string">'sales'</span> => <span class="string">'Vertrieb'</span>
    ], [<span class="string">'required'</span> => <span class="keyword">true</span>])
    
    -><span class="method">textarea</span>(<span class="string">'message'</span>, <span class="string">'Ihre Nachricht'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'rows'</span> => <span class="string">6</span>,
        <span class="string">'placeholder'</span> => <span class="string">'Ihre Nachricht...'</span>
    ])
    
    -><span class="method">checkbox</span>(<span class="string">'newsletter'</span>, <span class="string">'Newsletter abonnieren'</span>, [
        <span class="string">'toggle'</span> => <span class="keyword">true</span>
    ])
    
    -><span class="method">divider</span>()
    
    -><span class="method">submit</span>(<span class="string">'Nachricht senden'</span>, [
        <span class="string">'icon'</span> => <span class="string">'send'</span>,
        <span class="string">'class'</span> => <span class="string">'primary'</span>
    ])
    -><span class="method">reset</span>(<span class="string">'Zurücksetzen'</span>);

<span class="comment">// Validierungsregeln</span>
<span class="variable">$form</span>
    -><span class="method">rule</span>(<span class="string">'firstname'</span>, <span class="string">'required|alpha'</span>)
    -><span class="method">rule</span>(<span class="string">'lastname'</span>, <span class="string">'required|alpha'</span>)
    -><span class="method">rule</span>(<span class="string">'email'</span>, <span class="string">'required|email'</span>)
    -><span class="method">rule</span>(<span class="string">'message'</span>, <span class="string">'required|minlength[10]'</span>);

<span class="comment">// Formular anzeigen</span>
<span class="variable">$form</span>-><span class="method">display</span>();</pre>
                </div>
                
                <div class="alert alert-success">
                    <i class="check icon"></i>
                    <div>
                        <strong>Fertig!</strong> Sie haben nun alle wichtigen Features von EasyForm kennengelernt. 
                        Besuchen Sie unsere <a href="examples/index.php">Beispiele</a> für weitere Inspirationen.
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <script src="jquery/jquery.min.js"></script>
    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active state
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Scroll to section
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Highlight current section on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.doc-section');
            const scrollPos = window.scrollY + 100;
            
            sections.forEach(section => {
                const top = section.offsetTop;
                const height = section.offsetHeight;
                const id = section.getAttribute('id');
                
                if (scrollPos >= top && scrollPos < top + height) {
                    document.querySelectorAll('.sidebar-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>