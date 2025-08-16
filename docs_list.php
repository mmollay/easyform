<?php
/**
 * EasyList Documentation
 * Comprehensive documentation for the EasyList data table generator
 */
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyList Dokumentation - Modern Data List Generator</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7f9fc;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* Header */
        .doc-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .doc-header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .doc-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .doc-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        
        .version-badge {
            display: inline-block;
            padding: 5px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 20px;
        }
        
        /* Layout */
        .doc-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
            min-height: calc(100vh - 200px);
        }
        
        /* Sidebar */
        .doc-sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            height: fit-content;
            position: sticky;
            top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            color: #718096;
            text-decoration: none;
            display: block;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover {
            background: #f7fafc;
            color: #667eea;
            transform: translateX(5px);
        }
        
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        /* Content */
        .doc-content {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .content-section {
            margin-bottom: 50px;
            scroll-margin-top: 30px;
        }
        
        .content-section h2 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .content-section h3 {
            font-size: 1.5rem;
            color: #4a5568;
            margin: 30px 0 15px;
        }
        
        .content-section h4 {
            font-size: 1.2rem;
            color: #718096;
            margin: 20px 0 10px;
        }
        
        .content-section p {
            color: #4a5568;
            margin-bottom: 15px;
        }
        
        /* Code blocks */
        .code-example {
            background: #1a202c;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            overflow-x: auto;
        }
        
        .code-example pre {
            margin: 0;
            color: #e2e8f0;
        }
        
        .code-example code {
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        /* Feature boxes */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .feature-box {
            background: #f7fafc;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .feature-box h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .feature-box p {
            font-size: 0.95rem;
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
            border-bottom: 2px solid #e2e8f0;
        }
        
        .doc-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .doc-table code {
            background: #f7fafc;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #e01e5a;
        }
        
        /* Alert boxes */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .alert-info {
            background: #ebf8ff;
            border-left: 4px solid #4299e1;
            color: #2b6cb0;
        }
        
        .alert-warning {
            background: #fffaf0;
            border-left: 4px solid #ed8936;
            color: #c05621;
        }
        
        .alert-success {
            background: #f0fff4;
            border-left: 4px solid #48bb78;
            color: #276749;
        }
        
        /* Buttons */
        .btn-group {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .doc-container {
                grid-template-columns: 1fr;
            }
            
            .doc-sidebar {
                position: static;
            }
            
            .doc-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="doc-header">
        <div class="doc-header-content">
            <h1>
                <i class="list icon"></i>
                EasyList Dokumentation
                <span class="version-badge">v1.0</span>
            </h1>
            <p>Moderne Datenlisten mit Filter, Suche, Sortierung und Export</p>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="doc-container">
        <!-- Sidebar -->
        <div class="doc-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">Erste Schritte</div>
                <ul class="sidebar-menu">
                    <li><a href="#introduction" class="active">Einführung</a></li>
                    <li><a href="#installation">Installation</a></li>
                    <li><a href="#quick-start">Schnellstart</a></li>
                    <li><a href="#basic-example">Basis-Beispiel</a></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Kern-Features</div>
                <ul class="sidebar-menu">
                    <li><a href="#data-source">Datenquellen</a></li>
                    <li><a href="#columns">Spalten definieren</a></li>
                    <li><a href="#search">Suche</a></li>
                    <li><a href="#filters">Filter</a></li>
                    <li><a href="#sorting">Sortierung</a></li>
                    <li><a href="#pagination">Pagination</a></li>
                    <li><a href="#export">Export</a></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Erweitert</div>
                <ul class="sidebar-menu">
                    <li><a href="#selection">Zeilen-Auswahl</a></li>
                    <li><a href="#bulk-actions">Bulk-Aktionen</a></li>
                    <li><a href="#ajax">AJAX Integration</a></li>
                    <li><a href="#formatting">Formatierung</a></li>
                    <li><a href="#styling">Styling</a></li>
                    <li><a href="#events">Events</a></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Tools</div>
                <ul class="sidebar-menu">
                    <li><a href="../list_generator.php"><i class="magic icon"></i> List Generator</a></li>
                    <li><a href="examples/04_list_demo.php"><i class="play icon"></i> Live Demo</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Content -->
        <div class="doc-content">
            <!-- Introduction -->
            <section id="introduction" class="content-section">
                <h2>Einführung</h2>
                <p>
                    <strong>EasyList</strong> ist eine leistungsstarke PHP-Bibliothek zur Generierung von interaktiven Datentabellen 
                    mit minimalem Aufwand. Mit einem eleganten Fluent Interface können Sie komplexe Listen mit Such-, Filter-, 
                    Sortier- und Export-Funktionen in wenigen Zeilen Code erstellen.
                </p>
                
                <div class="feature-grid">
                    <div class="feature-box">
                        <h4><i class="search icon"></i> Live-Suche</h4>
                        <p>Echtzeit-Suche über alle oder ausgewählte Spalten</p>
                    </div>
                    <div class="feature-box">
                        <h4><i class="filter icon"></i> Erweiterte Filter</h4>
                        <p>Text-, Select-, Datum- und Bereichsfilter</p>
                    </div>
                    <div class="feature-box">
                        <h4><i class="sort icon"></i> Sortierung</h4>
                        <p>Klickbare Spaltenköpfe für auf-/absteigende Sortierung</p>
                    </div>
                    <div class="feature-box">
                        <h4><i class="file excel icon"></i> Export</h4>
                        <p>CSV, Excel und JSON Export-Funktionen</p>
                    </div>
                </div>
            </section>
            
            <!-- Installation -->
            <section id="installation" class="content-section">
                <h2>Installation</h2>
                
                <h3>Voraussetzungen</h3>
                <ul>
                    <li>PHP 7.4 oder höher</li>
                    <li>Semantic UI (für Styling)</li>
                    <li>jQuery (für interaktive Features)</li>
                </ul>
                
                <h3>Einbindung</h3>
                <p>Binden Sie die EasyList-Klasse über den Autoloader ein:</p>
                
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('<?php
require_once \'easy_form/autoload.php\';
use EasyForm\EasyList;

// Ihre Liste erstellen
$list = new EasyList(\'my_list\');
?>'); ?></code></pre>
                </div>
                
                <h3>JavaScript einbinden</h3>
                <p>Für interaktive Features benötigen Sie die EasyList JavaScript-Datei:</p>
                
                <div class="code-example">
                    <pre><code class="language-html"><?php echo htmlspecialchars('<script src="easy_form/js/easylist.js"></script>'); ?></code></pre>
                </div>
            </section>
            
            <!-- Quick Start -->
            <section id="quick-start" class="content-section">
                <h2>Schnellstart</h2>
                <p>Erstellen Sie Ihre erste Liste in drei einfachen Schritten:</p>
                
                <h3>1. Daten vorbereiten</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$data = [
    [\'id\' => 1, \'name\' => \'Max Mustermann\', \'email\' => \'max@example.com\', \'status\' => \'Aktiv\'],
    [\'id\' => 2, \'name\' => \'Anna Schmidt\', \'email\' => \'anna@example.com\', \'status\' => \'Inaktiv\'],
    [\'id\' => 3, \'name\' => \'Tom Weber\', \'email\' => \'tom@example.com\', \'status\' => \'Aktiv\']
];'); ?></code></pre>
                </div>
                
                <h3>2. Liste konfigurieren</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list = new EasyList(\'user_list\');

$list->data($data)
    ->column(\'id\', \'ID\', [\'width\' => \'80px\'])
    ->column(\'name\', \'Name\')
    ->column(\'email\', \'E-Mail\', [\'type\' => \'email\'])
    ->column(\'status\', \'Status\', [
        \'filter\' => [\'type\' => \'select\', \'options\' => [\'Aktiv\', \'Inaktiv\']]
    ])
    ->searchable(true)
    ->sortable(true)
    ->paginate(true, 10);'); ?></code></pre>
                </div>
                
                <h3>3. Liste anzeigen</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->display();'); ?></code></pre>
                </div>
            </section>
            
            <!-- Basic Example -->
            <section id="basic-example" class="content-section">
                <h2>Basis-Beispiel</h2>
                <p>Ein vollständiges Beispiel einer Benutzerliste mit allen Standard-Features:</p>
                
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('<?php
require_once \'easy_form/autoload.php\';
use EasyForm\EasyList;

// Beispieldaten
$users = [
    [\'id\' => 1, \'name\' => \'Max Mustermann\', \'email\' => \'max@example.com\', 
     \'role\' => \'Admin\', \'status\' => \'Aktiv\', \'created\' => \'2024-01-15\'],
    [\'id\' => 2, \'name\' => \'Anna Schmidt\', \'email\' => \'anna@example.com\', 
     \'role\' => \'User\', \'status\' => \'Aktiv\', \'created\' => \'2024-02-20\'],
    [\'id\' => 3, \'name\' => \'Tom Weber\', \'email\' => \'tom@example.com\', 
     \'role\' => \'Editor\', \'status\' => \'Inaktiv\', \'created\' => \'2024-03-10\']
];

// Liste erstellen
$userList = new EasyList(\'users\');

$userList->data($users)
    ->column(\'id\', \'ID\', [\'width\' => \'60px\', \'align\' => \'center\'])
    ->column(\'name\', \'Name\', [\'sortable\' => true])
    ->column(\'email\', \'E-Mail\', [\'type\' => \'email\'])
    ->column(\'role\', \'Rolle\', [
        \'filter\' => [
            \'type\' => \'select\',
            \'options\' => [\'Admin\', \'User\', \'Editor\']
        ]
    ])
    ->column(\'status\', \'Status\', [
        \'format\' => function($value) {
            $color = $value === \'Aktiv\' ? \'green\' : \'red\';
            return \'<span class="ui \' . $color . \' label">\' . $value . \'</span>\';
        }
    ])
    ->column(\'created\', \'Erstellt\', [\'type\' => \'date\'])
    ->searchable(true, \'Benutzer suchen...\')
    ->sortable(true)
    ->paginate(true, 25)
    ->exportable(true, [\'csv\', \'excel\', \'json\'])
    ->display();
?>'); ?></code></pre>
                </div>
            </section>
            
            <!-- Data Sources -->
            <section id="data-source" class="content-section">
                <h2>Datenquellen</h2>
                <p>EasyList unterstützt verschiedene Datenquellen:</p>
                
                <h3>PHP-Array</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$data = [
    [\'col1\' => \'value1\', \'col2\' => \'value2\'],
    [\'col1\' => \'value3\', \'col2\' => \'value4\']
];
$list->data($data);'); ?></code></pre>
                </div>
                
                <h3>Datenbank (geplant)</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->fromDatabase(\'SELECT * FROM users\', $pdo);'); ?></code></pre>
                </div>
                
                <h3>API/AJAX</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->fromApi(\'/api/users\', [
    \'method\' => \'GET\',
    \'headers\' => [\'Authorization\' => \'Bearer token\']
]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Columns -->
            <section id="columns" class="content-section">
                <h2>Spalten definieren</h2>
                <p>Spalten können mit verschiedenen Optionen konfiguriert werden:</p>
                
                <h3>Basis-Spalte</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'field_name\', \'Anzeigename\');'); ?></code></pre>
                </div>
                
                <h3>Erweiterte Optionen</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Typ</th>
                            <th>Beschreibung</th>
                            <th>Beispiel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>type</code></td>
                            <td>string</td>
                            <td>Datentyp (text, number, date, email, status)</td>
                            <td><code>'type' => 'email'</code></td>
                        </tr>
                        <tr>
                            <td><code>width</code></td>
                            <td>string</td>
                            <td>Spaltenbreite</td>
                            <td><code>'width' => '150px'</code></td>
                        </tr>
                        <tr>
                            <td><code>align</code></td>
                            <td>string</td>
                            <td>Ausrichtung (left, center, right)</td>
                            <td><code>'align' => 'center'</code></td>
                        </tr>
                        <tr>
                            <td><code>sortable</code></td>
                            <td>boolean</td>
                            <td>Sortierbar ja/nein</td>
                            <td><code>'sortable' => false</code></td>
                        </tr>
                        <tr>
                            <td><code>searchable</code></td>
                            <td>boolean</td>
                            <td>Durchsuchbar ja/nein</td>
                            <td><code>'searchable' => false</code></td>
                        </tr>
                        <tr>
                            <td><code>format</code></td>
                            <td>callable</td>
                            <td>Custom Formatierung</td>
                            <td><code>'format' => function($val) { ... }</code></td>
                        </tr>
                        <tr>
                            <td><code>filter</code></td>
                            <td>array</td>
                            <td>Filter-Konfiguration</td>
                            <td><code>'filter' => ['type' => 'select']</code></td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Formatierung mit Callbacks</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'price\', \'Preis\', [
    \'type\' => \'number\',
    \'align\' => \'right\',
    \'format\' => function($value, $row) {
        return \'€ \' . number_format($value, 2, \',\', \'.\');
    }
]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Search -->
            <section id="search" class="content-section">
                <h2>Suche</h2>
                <p>Aktivieren Sie die Live-Suche für Ihre Liste:</p>
                
                <h3>Einfache Suche</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->searchable(true);'); ?></code></pre>
                </div>
                
                <h3>Mit Placeholder</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->searchable(true, \'Produkte durchsuchen...\');'); ?></code></pre>
                </div>
                
                <h3>Spalten von Suche ausschließen</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'id\', \'ID\', [\'searchable\' => false]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Filters -->
            <section id="filters" class="content-section">
                <h2>Filter</h2>
                <p>Verschiedene Filter-Typen für unterschiedliche Datentypen:</p>
                
                <h3>Text-Filter</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'name\', \'Name\', [
    \'filter\' => [
        \'type\' => \'text\',
        \'placeholder\' => \'Name filtern...\'
    ]
]);'); ?></code></pre>
                </div>
                
                <h3>Select-Filter</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'status\', \'Status\', [
    \'filter\' => [
        \'type\' => \'select\',
        \'options\' => [
            \'active\' => \'Aktiv\',
            \'inactive\' => \'Inaktiv\',
            \'pending\' => \'Ausstehend\'
        ]
    ]
]);'); ?></code></pre>
                </div>
                
                <h3>Datums-Filter</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'created\', \'Erstellt\', [
    \'type\' => \'date\',
    \'filter\' => [
        \'type\' => \'date\'
    ]
]);'); ?></code></pre>
                </div>
                
                <h3>Bereichs-Filter</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'price\', \'Preis\', [
    \'type\' => \'number\',
    \'filter\' => [
        \'type\' => \'range\',
        \'min\' => 0,
        \'max\' => 1000
    ]
]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Sorting -->
            <section id="sorting" class="content-section">
                <h2>Sortierung</h2>
                
                <h3>Sortierung aktivieren</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->sortable(true);'); ?></code></pre>
                </div>
                
                <h3>Standard-Sortierung setzen</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->sortable(true)
    ->defaultSort(\'created\', \'desc\');'); ?></code></pre>
                </div>
                
                <h3>Spalten von Sortierung ausschließen</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'actions\', \'Aktionen\', [\'sortable\' => false]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Pagination -->
            <section id="pagination" class="content-section">
                <h2>Pagination</h2>
                
                <h3>Einfache Pagination</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->paginate(true); // Standard: 25 Einträge pro Seite'); ?></code></pre>
                </div>
                
                <h3>Mit Custom Page Size</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->paginate(true, 50); // 50 Einträge pro Seite'); ?></code></pre>
                </div>
                
                <h3>Mit Page Size Selector</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->paginate(true, 25)
    ->pageSizeOptions([10, 25, 50, 100]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Export -->
            <section id="export" class="content-section">
                <h2>Export</h2>
                <p>Exportieren Sie Ihre Daten in verschiedene Formate:</p>
                
                <h3>Export aktivieren</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->exportable(true); // Alle Formate'); ?></code></pre>
                </div>
                
                <h3>Spezifische Formate</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->exportable(true, [\'csv\', \'excel\']);'); ?></code></pre>
                </div>
                
                <h3>Verfügbare Formate</h3>
                <ul>
                    <li><strong>CSV</strong> - Comma Separated Values</li>
                    <li><strong>Excel</strong> - Microsoft Excel (XLS)</li>
                    <li><strong>JSON</strong> - JavaScript Object Notation</li>
                    <li><strong>PDF</strong> - Portable Document Format (geplant)</li>
                </ul>
            </section>
            
            <!-- Selection -->
            <section id="selection" class="content-section">
                <h2>Zeilen-Auswahl</h2>
                
                <h3>Checkbox-Auswahl aktivieren</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->selectable(true); // Standard: Checkbox'); ?></code></pre>
                </div>
                
                <h3>Radio-Button Auswahl</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->selectable(true, \'radio\');'); ?></code></pre>
                </div>
            </section>
            
            <!-- Bulk Actions -->
            <section id="bulk-actions" class="content-section">
                <h2>Bulk-Aktionen</h2>
                <p>Führen Sie Aktionen auf mehreren ausgewählten Zeilen aus:</p>
                
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->bulkActions([
    \'delete\' => [
        \'label\' => \'Löschen\',
        \'icon\' => \'trash\',
        \'confirm\' => \'Wirklich löschen?\'
    ],
    \'activate\' => [
        \'label\' => \'Aktivieren\',
        \'icon\' => \'check\'
    ],
    \'export\' => [
        \'label\' => \'Exportieren\',
        \'icon\' => \'download\'
    ]
]);'); ?></code></pre>
                </div>
                
                <h3>JavaScript Event Handler</h3>
                <div class="code-example">
                    <pre><code class="language-javascript"><?php echo htmlspecialchars('document.addEventListener(\'easylist:bulkaction\', function(e) {
    const { action, ids, listId } = e.detail;
    
    switch(action) {
        case \'delete\':
            // Lösch-Logik
            console.log(\'Lösche IDs:\', ids);
            break;
        case \'activate\':
            // Aktivierungs-Logik
            break;
    }
});'); ?></code></pre>
                </div>
            </section>
            
            <!-- AJAX Integration -->
            <section id="ajax" class="content-section">
                <h2>AJAX Integration</h2>
                
                <h3>AJAX-Datenquelle</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->fromApi(\'/api/users\', [
    \'method\' => \'GET\',
    \'cache\' => true,
    \'refresh\' => 30 // Sekunden
]);'); ?></code></pre>
                </div>
                
                <h3>Live-Updates</h3>
                <div class="code-example">
                    <pre><code class="language-javascript"><?php echo htmlspecialchars('// Daten aktualisieren
function refreshList() {
    const list = document.getElementById(\'my_list\');
    // Trigger refresh
    list.dispatchEvent(new Event(\'easylist:refresh\'));
}

// Auto-refresh alle 30 Sekunden
setInterval(refreshList, 30000);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Formatting -->
            <section id="formatting" class="content-section">
                <h2>Formatierung</h2>
                
                <h3>Status-Labels</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'status\', \'Status\', [
    \'format\' => function($value) {
        $colors = [
            \'active\' => \'green\',
            \'inactive\' => \'red\',
            \'pending\' => \'yellow\'
        ];
        $color = $colors[$value] ?? \'grey\';
        return sprintf(
            \'<span class="ui %s label">%s</span>\',
            $color,
            ucfirst($value)
        );
    }
]);'); ?></code></pre>
                </div>
                
                <h3>Währungsformatierung</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'price\', \'Preis\', [
    \'type\' => \'number\',
    \'align\' => \'right\',
    \'format\' => function($value) {
        return \'€ \' . number_format($value, 2, \',\', \'.\');
    }
]);'); ?></code></pre>
                </div>
                
                <h3>Datumsformatierung</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->column(\'created\', \'Erstellt\', [
    \'type\' => \'date\',
    \'format\' => function($value) {
        return date(\'d.m.Y H:i\', strtotime($value));
    }
]);'); ?></code></pre>
                </div>
            </section>
            
            <!-- Styling -->
            <section id="styling" class="content-section">
                <h2>Styling</h2>
                
                <h3>Tabellen-Styles</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->style(\'ui celled table\', [
    \'striped\' => true,
    \'compact\' => true,
    \'hover\' => true
]);'); ?></code></pre>
                </div>
                
                <h3>Verfügbare Optionen</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Beschreibung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>striped</code></td>
                            <td>Abwechselnde Zeilenfarben</td>
                        </tr>
                        <tr>
                            <td><code>compact</code></td>
                            <td>Reduziertes Padding</td>
                        </tr>
                        <tr>
                            <td><code>hover</code></td>
                            <td>Hover-Effekt auf Zeilen</td>
                        </tr>
                        <tr>
                            <td><code>color</code></td>
                            <td>Farbschema (red, orange, yellow, olive, green, teal, blue, violet, purple, pink, brown, grey, black)</td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Custom CSS</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('$list->customCSS(\'
    #my_list tbody tr:hover {
        background: #f0f0f0;
        cursor: pointer;
    }
\');'); ?></code></pre>
                </div>
            </section>
            
            <!-- Events -->
            <section id="events" class="content-section">
                <h2>Events</h2>
                <p>EasyList triggert verschiedene JavaScript-Events:</p>
                
                <h3>Verfügbare Events</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Beschreibung</th>
                            <th>Detail-Daten</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>easylist:ready</code></td>
                            <td>Liste wurde initialisiert</td>
                            <td>listId</td>
                        </tr>
                        <tr>
                            <td><code>easylist:search</code></td>
                            <td>Suche wurde ausgeführt</td>
                            <td>searchTerm, results</td>
                        </tr>
                        <tr>
                            <td><code>easylist:sort</code></td>
                            <td>Sortierung geändert</td>
                            <td>column, direction</td>
                        </tr>
                        <tr>
                            <td><code>easylist:filter</code></td>
                            <td>Filter angewendet</td>
                            <td>filters, results</td>
                        </tr>
                        <tr>
                            <td><code>easylist:page</code></td>
                            <td>Seite gewechselt</td>
                            <td>page, pageSize</td>
                        </tr>
                        <tr>
                            <td><code>easylist:select</code></td>
                            <td>Zeile ausgewählt</td>
                            <td>ids, selected</td>
                        </tr>
                        <tr>
                            <td><code>easylist:bulkaction</code></td>
                            <td>Bulk-Aktion ausgeführt</td>
                            <td>action, ids</td>
                        </tr>
                        <tr>
                            <td><code>easylist:export</code></td>
                            <td>Export gestartet</td>
                            <td>format, data</td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Event Listener Beispiel</h3>
                <div class="code-example">
                    <pre><code class="language-javascript"><?php echo htmlspecialchars('// Auf alle EasyList Events hören
document.addEventListener(\'easylist:ready\', function(e) {
    console.log(\'Liste bereit:\', e.detail.listId);
});

document.addEventListener(\'easylist:search\', function(e) {
    console.log(\'Suche:\', e.detail.searchTerm);
    console.log(\'Ergebnisse:\', e.detail.results.length);
});

document.addEventListener(\'easylist:select\', function(e) {
    console.log(\'Ausgewählte IDs:\', e.detail.ids);
});'); ?></code></pre>
                </div>
            </section>
            
            <!-- Complete Examples -->
            <section id="complete-examples" class="content-section">
                <h2>Vollständige Beispiele</h2>
                
                <h3>Produktkatalog</h3>
                <div class="code-example">
                    <pre><code class="language-php"><?php echo htmlspecialchars('<?php
$products = [
    [\'sku\' => \'PRD001\', \'name\' => \'Laptop\', \'category\' => \'Elektronik\', 
     \'price\' => 999.99, \'stock\' => 15, \'status\' => \'Verfügbar\'],
    // ... mehr Produkte
];

$productList = new EasyList(\'products\');
$productList
    ->data($products)
    ->column(\'sku\', \'SKU\', [\'width\' => \'100px\'])
    ->column(\'name\', \'Produkt\', [\'searchable\' => true])
    ->column(\'category\', \'Kategorie\', [
        \'filter\' => [
            \'type\' => \'select\',
            \'options\' => [\'Elektronik\', \'Bücher\', \'Kleidung\']
        ]
    ])
    ->column(\'price\', \'Preis\', [
        \'type\' => \'number\',
        \'align\' => \'right\',
        \'format\' => function($val) {
            return \'€ \' . number_format($val, 2, \',\', \'.\');
        },
        \'filter\' => [\'type\' => \'range\', \'min\' => 0, \'max\' => 2000]
    ])
    ->column(\'stock\', \'Lager\', [
        \'type\' => \'number\',
        \'align\' => \'center\',
        \'format\' => function($val) {
            $color = $val > 10 ? \'green\' : ($val > 0 ? \'yellow\' : \'red\');
            return \'<span class="ui \' . $color . \' circular label">\' . $val . \'</span>\';
        }
    ])
    ->column(\'status\', \'Status\', [
        \'format\' => function($val) {
            $color = $val === \'Verfügbar\' ? \'green\' : \'red\';
            return \'<span class="ui \' . $color . \' label">\' . $val . \'</span>\';
        }
    ])
    ->searchable(true, \'Produkte suchen...\')
    ->sortable(true)
    ->paginate(true, 20)
    ->exportable(true, [\'csv\', \'excel\'])
    ->actions([
        [\'label\' => \'Bearbeiten\', \'icon\' => \'edit\', \'url\' => \'edit.php?id={sku}\'],
        [\'label\' => \'Löschen\', \'icon\' => \'trash\', \'url\' => \'delete.php?id={sku}\']
    ])
    ->display();
?>'); ?></code></pre>
                </div>
                
                <div class="alert alert-info">
                    <strong>Tipp:</strong> Verwenden Sie den <a href="../list_generator.php">List Generator</a>, 
                    um Ihre Listen visuell zu erstellen und den Code automatisch zu generieren!
                </div>
            </section>
            
            <!-- Best Practices -->
            <section id="best-practices" class="content-section">
                <h2>Best Practices</h2>
                
                <h3>Performance</h3>
                <ul>
                    <li>Begrenzen Sie die Anzahl der angezeigten Zeilen mit Pagination</li>
                    <li>Deaktivieren Sie Suche/Sortierung für große Datenmengen serverseitig</li>
                    <li>Verwenden Sie AJAX für Daten über 1000 Zeilen</li>
                    <li>Minimieren Sie Custom-Formatierungen</li>
                </ul>
                
                <h3>Usability</h3>
                <ul>
                    <li>Verwenden Sie aussagekräftige Spaltennamen</li>
                    <li>Gruppieren Sie zusammengehörige Spalten</li>
                    <li>Bieten Sie relevante Filter-Optionen</li>
                    <li>Nutzen Sie visuelle Indikatoren (Farben, Icons)</li>
                </ul>
                
                <h3>Sicherheit</h3>
                <ul>
                    <li>Escapen Sie alle Ausgaben mit <code>htmlspecialchars()</code></li>
                    <li>Validieren Sie Filter-Eingaben serverseitig</li>
                    <li>Implementieren Sie CSRF-Schutz für Aktionen</li>
                    <li>Begrenzen Sie Export-Größen</li>
                </ul>
            </section>
            
            <!-- Footer -->
            <div class="btn-group" style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #e2e8f0;">
                <a href="../list_generator.php" class="btn btn-primary">
                    <i class="magic icon"></i> List Generator öffnen
                </a>
                <a href="examples/04_list_demo.php" class="btn btn-secondary">
                    <i class="play icon"></i> Live Demo ansehen
                </a>
                <a href="docs.php" class="btn btn-secondary">
                    <i class="book icon"></i> EasyForm Docs
                </a>
            </div>
        </div>
    </div>
    
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                // Remove active class from all links
                document.querySelectorAll('.sidebar-menu a').forEach(link => {
                    link.classList.remove('active');
                });
                
                // Add active class to clicked link
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
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.content-section');
            const scrollPos = window.scrollY + 100;
            
            sections.forEach(section => {
                const top = section.offsetTop;
                const height = section.offsetHeight;
                const id = section.getAttribute('id');
                
                if (scrollPos >= top && scrollPos <= top + height) {
                    document.querySelectorAll('.sidebar-menu a').forEach(link => {
                        link.classList.remove('active');
                    });
                    document.querySelector(`.sidebar-menu a[href="#${id}"]`)?.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>