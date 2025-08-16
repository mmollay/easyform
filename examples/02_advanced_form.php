<?php
/**
 * EasyForm Beispiel 2: Formular mit Tabs und Gruppen
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyForm;

// Formular mit Tabs
$form = new EasyForm('application_form', [
    'width' => 800,
    'size' => 'large'
]);

// Formular mit Tabs
$form->action('process.php')
    ->ajax([
        'success' => 'function(response) { 
            if(response.success) {
                $("#result").html("<div class=\"ui success message\"><i class=\"check icon\"></i> " + response.message + "</div>");
                if(response.data.application_id) {
                    $("#result").append("<div class=\"ui info message\">Ihre Bewerbungsnummer: <strong>" + response.data.application_id + "</strong></div>");
                }
            } else {
                $("#result").html("<div class=\"ui error message\"><i class=\"times icon\"></i> " + response.message + "</div>");
            }
        }',
        'error' => 'function(xhr) { 
            $("#result").html("<div class=\"ui error message\"><i class=\"times icon\"></i> Fehler beim Senden!</div>");
        }'
    ])
    ->html('<input type="hidden" name="form_id" value="application_form">')
    ->heading('Bewerbungsformular', 2)
    ->html('<p class="ui message info">Bitte füllen Sie alle erforderlichen Felder aus.</p>')
    
    // Tab 1: Persönliche Daten
    ->group('personal', 'Persönliche Daten', ['type' => 'tab', 'active' => true, 'icon' => 'user'])
        // Vorname und Nachname nebeneinander
        ->row(['columns' => 2])
            ->col()
                ->text('firstname', 'Vorname', ['required' => true])
            ->endCol()
            ->col()
                ->text('lastname', 'Nachname', ['required' => true])
            ->endCol()
        ->endRow()
        // Email und Telefon nebeneinander
        ->row(['columns' => 2])
            ->col()
                ->email('email', 'E-Mail', ['required' => true])
            ->endCol()
            ->col()
                ->tel('phone', 'Telefon')
            ->endCol()
        ->endRow()
        // Geburtsdatum einzeln
        ->date('birthdate', 'Geburtsdatum', ['required' => true])
    ->endGroup()
    
    // Tab 2: Adresse
    ->group('address', 'Adresse', ['type' => 'tab', 'icon' => 'home'])
        // Straße auf voller Breite
        ->text('street', 'Straße und Hausnummer')
        // PLZ und Stadt nebeneinander
        ->row()
            ->col(4)
                ->text('zip', 'PLZ', ['placeholder' => '12345'])
            ->endCol()
            ->col(12)
                ->text('city', 'Stadt')
            ->endCol()
        ->endRow()
        // Land
        ->select('country', 'Land', [
            'de' => 'Deutschland',
            'at' => 'Österreich',
            'ch' => 'Schweiz',
            'other' => 'Andere'
        ])
    ->endGroup()
    
    // Tab 3: Qualifikationen
    ->group('qualifications', 'Qualifikationen', ['type' => 'tab', 'icon' => 'graduation'])
        ->select('education', 'Höchster Abschluss', [
            'hauptschule' => 'Hauptschulabschluss',
            'realschule' => 'Realschulabschluss',
            'abitur' => 'Abitur',
            'bachelor' => 'Bachelor',
            'master' => 'Master',
            'phd' => 'Promotion'
        ], ['required' => true])
        ->range('experience', 'Jahre Berufserfahrung', [
            'min' => 0,
            'max' => 40,
            'value' => 5,
            'unit' => 'Jahre'
        ])
        ->textarea('skills', 'Ihre Fähigkeiten', [
            'rows' => 4,
            'placeholder' => 'Beschreiben Sie Ihre wichtigsten Fähigkeiten...'
        ])
    ->endGroup()
    
    // Tab 4: Dokumente
    ->group('documents', 'Dokumente', ['type' => 'tab', 'icon' => 'file'])
        ->text('cv_placeholder', 'Lebenslauf', [
            'placeholder' => 'Name Ihrer CV-Datei',
            'help' => 'In der finalen Version: Datei-Upload für PDF'
        ])
        ->text('certificates_placeholder', 'Zeugnisse', [
            'placeholder' => 'Namen Ihrer Zeugnisse',
            'help' => 'In der finalen Version: Multi-Datei-Upload'
        ])
        ->url('portfolio', 'Portfolio/Website', [
            'placeholder' => 'https://ihre-website.de'
        ])
    ->endGroup()
    
    ->divider()
    ->checkbox('terms', 'Ich akzeptiere die Datenschutzbestimmungen', ['required' => true])
    ->submit('Bewerbung absenden', ['icon' => 'send', 'class' => 'primary large'])
    ->button('Zurücksetzen', ['type' => 'reset', 'class' => 'secondary']);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Erweiterte Features</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(120deg, #f6f8fb 0%, #e9ecf0 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1400px;
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
        .content-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        @media (max-width: 1200px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .code-sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
        }
        .code-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .card-header h3 {
            font-size: 1.2rem;
            color: #2d3748;
        }
        .card-header i {
            color: #667eea;
            font-size: 1.3rem;
        }
        .code-block {
            background: #1a202c;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 10px;
            overflow-x: auto;
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 0.85rem;
            line-height: 1.5;
            max-height: 400px;
            overflow-y: auto;
        }
        .code-block::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .code-block::-webkit-scrollbar-track {
            background: #2d3748;
        }
        .code-block::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 4px;
        }
        .code-block .comment { color: #718096; }
        .code-block .keyword { color: #ed64a6; }
        .code-block .string { color: #68d391; }
        .code-block .variable { color: #63b3ed; }
        .code-block .method { color: #fbb040; }
        .feature-box {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .feature-box h4 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .feature-box ul {
            list-style: none;
            padding: 0;
        }
        .feature-box li {
            padding: 5px 0;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .feature-box li i {
            color: #48bb78;
            font-size: 0.9rem;
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
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
            color: #667eea;
        }
        
        /* Fehler-Styling */
        .ui.form .field.error input,
        .ui.form .field.error select,
        .ui.form .field.error textarea {
            background: #fff6f6 !important;
            border-color: #e0b4b4 !important;
            color: #9f3a38 !important;
        }
        
        .ui.form .field.error label {
            color: #9f3a38 !important;
        }
        
        .ui.pointing.red.basic.label {
            margin-top: 5px !important;
        }
        
        /* Tab mit Fehler markieren */
        .ui.tabular.menu .item.error {
            color: #db2828 !important;
            border-color: #db2828 !important;
        }
        
        .ui.tabular.menu .item.error:before {
            content: "⚠";
            margin-right: 5px;
            color: #db2828;
        }
        
        /* Grid/Fields Abstände korrigieren */
        .ui.form .fields {
            margin-bottom: 1em !important;
        }
        
        .ui.form .fields:last-child {
            margin-bottom: 0 !important;
        }
        
        .ui.form .field {
            margin-bottom: 1em;
        }
        
        .ui.form .fields > .field {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- jQuery und Semantic UI MÜSSEN VOR dem Formular geladen werden -->
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <div class="container">
        <!-- Header -->
        <div class="header-section">
            <h1>Erweiterte Features Demo</h1>
            <p>Tabs, Grid-Layout, Gruppen und komplexe Validierung</p>
            <a href="index.php" class="back-button">
                <i class="arrow left icon"></i> Zurück zur Übersicht
            </a>
        </div>
        
        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Form Display -->
            <div class="form-container">
                <div id="result"></div>
                <?php $form->display(); ?>
            </div>
            
            <!-- Code Sidebar -->
            <div class="code-sidebar">
                <!-- Tab Code Example -->
                <div class="code-card">
                    <div class="card-header">
                        <i class="code icon"></i>
                        <h3>Tab-Navigation</h3>
                    </div>
                    <div class="code-block">
<pre><span class="comment">// Tab-Gruppen erstellen</span>
<span class="variable">$form</span>
  -><span class="method">group</span>(<span class="string">'personal'</span>, <span class="string">'Persönliche Daten'</span>, [
      <span class="string">'type'</span> => <span class="string">'tab'</span>,
      <span class="string">'active'</span> => <span class="keyword">true</span>,
      <span class="string">'icon'</span> => <span class="string">'user'</span>
  ])
    -><span class="method">text</span>(<span class="string">'firstname'</span>, <span class="string">'Vorname'</span>)
    -><span class="method">text</span>(<span class="string">'lastname'</span>, <span class="string">'Nachname'</span>)
  -><span class="method">endGroup</span>()
  
  -><span class="method">group</span>(<span class="string">'address'</span>, <span class="string">'Adresse'</span>, [
      <span class="string">'type'</span> => <span class="string">'tab'</span>,
      <span class="string">'icon'</span> => <span class="string">'home'</span>
  ])
    -><span class="method">text</span>(<span class="string">'street'</span>, <span class="string">'Straße'</span>)
    -><span class="method">text</span>(<span class="string">'city'</span>, <span class="string">'Stadt'</span>)
  -><span class="method">endGroup</span>();</pre>
                    </div>
                </div>
                
                <!-- Grid Layout Example -->
                <div class="code-card">
                    <div class="card-header">
                        <i class="grid layout icon"></i>
                        <h3>Grid Layout</h3>
                    </div>
                    <div class="code-block">
<pre><span class="comment">// 2-Spalten Layout</span>
<span class="variable">$form</span>
  -><span class="method">row</span>([<span class="string">'columns'</span> => <span class="string">2</span>])
    -><span class="method">col</span>()
      -><span class="method">text</span>(<span class="string">'firstname'</span>, <span class="string">'Vorname'</span>)
    -><span class="method">endCol</span>()
    -><span class="method">col</span>()
      -><span class="method">text</span>(<span class="string">'lastname'</span>, <span class="string">'Nachname'</span>)
    -><span class="method">endCol</span>()
  -><span class="method">endRow</span>()
  
  <span class="comment">// Responsive Grid</span>
  -><span class="method">row</span>()
    -><span class="method">col</span>(<span class="string">4</span>) <span class="comment">// 4 von 16</span>
      -><span class="method">text</span>(<span class="string">'zip'</span>, <span class="string">'PLZ'</span>)
    -><span class="method">endCol</span>()
    -><span class="method">col</span>(<span class="string">12</span>) <span class="comment">// 12 von 16</span>
      -><span class="method">text</span>(<span class="string">'city'</span>, <span class="string">'Stadt'</span>)
    -><span class="method">endCol</span>()
  -><span class="method">endRow</span>();</pre>
                    </div>
                </div>
                
                <!-- Features Used -->
                <div class="code-card">
                    <div class="card-header">
                        <i class="star icon"></i>
                        <h3>Verwendete Features</h3>
                    </div>
                    <div class="feature-box">
                        <h4>Layout & Struktur</h4>
                        <ul>
                            <li><i class="check icon"></i> Tab-Navigation</li>
                            <li><i class="check icon"></i> Grid-System (Rows/Columns)</li>
                            <li><i class="check icon"></i> Gruppen & Sections</li>
                            <li><i class="check icon"></i> Responsive Layout</li>
                        </ul>
                    </div>
                    <div class="feature-box">
                        <h4>Feldtypen</h4>
                        <ul>
                            <li><i class="check icon"></i> Text, Email, Tel, Date</li>
                            <li><i class="check icon"></i> Select mit Optionen</li>
                            <li><i class="check icon"></i> Range Slider</li>
                            <li><i class="check icon"></i> Textarea</li>
                            <li><i class="check icon"></i> Checkbox & Radio</li>
                            <li><i class="check icon"></i> URL Field</li>
                        </ul>
                    </div>
                    <div class="feature-box">
                        <h4>Validierung</h4>
                        <ul>
                            <li><i class="check icon"></i> Required Fields</li>
                            <li><i class="check icon"></i> Client-seitige Validierung</li>
                            <li><i class="check icon"></i> Fehlermarkierung in Tabs</li>
                            <li><i class="check icon"></i> Live-Feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Full Code Example -->
        <div class="code-card">
            <div class="card-header">
                <i class="file code icon"></i>
                <h3>Vollständiger Code</h3>
            </div>
            <div class="code-block" style="max-height: 500px;">
<pre><span class="keyword">use</span> EasyForm\EasyForm;

<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'application_form'</span>, [
    <span class="string">'width'</span> => <span class="string">800</span>,
    <span class="string">'size'</span> => <span class="string">'large'</span>
]);

<span class="variable">$form</span>-><span class="method">action</span>(<span class="string">'process.php'</span>)
    -><span class="method">ajax</span>([
        <span class="string">'success'</span> => <span class="string">'function(response) { ... }'</span>,
        <span class="string">'error'</span> => <span class="string">'function(xhr) { ... }'</span>
    ])
    -><span class="method">heading</span>(<span class="string">'Bewerbungsformular'</span>, <span class="string">2</span>)
    
    <span class="comment">// Tab 1: Persönliche Daten mit Grid Layout</span>
    -><span class="method">group</span>(<span class="string">'personal'</span>, <span class="string">'Persönliche Daten'</span>, [
        <span class="string">'type'</span> => <span class="string">'tab'</span>, 
        <span class="string">'active'</span> => <span class="keyword">true</span>, 
        <span class="string">'icon'</span> => <span class="string">'user'</span>
    ])
        <span class="comment">// Vorname und Nachname nebeneinander</span>
        -><span class="method">row</span>([<span class="string">'columns'</span> => <span class="string">2</span>])
            -><span class="method">col</span>()
                -><span class="method">text</span>(<span class="string">'firstname'</span>, <span class="string">'Vorname'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
            -><span class="method">endCol</span>()
            -><span class="method">col</span>()
                -><span class="method">text</span>(<span class="string">'lastname'</span>, <span class="string">'Nachname'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
            -><span class="method">endCol</span>()
        -><span class="method">endRow</span>()
        
        <span class="comment">// Email und Telefon nebeneinander</span>
        -><span class="method">row</span>([<span class="string">'columns'</span> => <span class="string">2</span>])
            -><span class="method">col</span>()
                -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
            -><span class="method">endCol</span>()
            -><span class="method">col</span>()
                -><span class="method">tel</span>(<span class="string">'phone'</span>, <span class="string">'Telefon'</span>)
            -><span class="method">endCol</span>()
        -><span class="method">endRow</span>()
        
        -><span class="method">date</span>(<span class="string">'birthdate'</span>, <span class="string">'Geburtsdatum'</span>, [<span class="string">'required'</span> => <span class="keyword">true</span>])
    -><span class="method">endGroup</span>()
    
    <span class="comment">// Tab 2: Adresse</span>
    -><span class="method">group</span>(<span class="string">'address'</span>, <span class="string">'Adresse'</span>, [<span class="string">'type'</span> => <span class="string">'tab'</span>, <span class="string">'icon'</span> => <span class="string">'home'</span>])
        -><span class="method">text</span>(<span class="string">'street'</span>, <span class="string">'Straße und Hausnummer'</span>)
        
        <span class="comment">// PLZ und Stadt mit unterschiedlicher Breite</span>
        -><span class="method">row</span>()
            -><span class="method">col</span>(<span class="string">4</span>)
                -><span class="method">text</span>(<span class="string">'zip'</span>, <span class="string">'PLZ'</span>, [<span class="string">'placeholder'</span> => <span class="string">'12345'</span>])
            -><span class="method">endCol</span>()
            -><span class="method">col</span>(<span class="string">12</span>)
                -><span class="method">text</span>(<span class="string">'city'</span>, <span class="string">'Stadt'</span>)
            -><span class="method">endCol</span>()
        -><span class="method">endRow</span>()
        
        -><span class="method">select</span>(<span class="string">'country'</span>, <span class="string">'Land'</span>, [
            <span class="string">'de'</span> => <span class="string">'Deutschland'</span>,
            <span class="string">'at'</span> => <span class="string">'Österreich'</span>,
            <span class="string">'ch'</span> => <span class="string">'Schweiz'</span>
        ])
    -><span class="method">endGroup</span>()
    
    <span class="comment">// Tab 3: Qualifikationen</span>
    -><span class="method">group</span>(<span class="string">'qualifications'</span>, <span class="string">'Qualifikationen'</span>, [
        <span class="string">'type'</span> => <span class="string">'tab'</span>, 
        <span class="string">'icon'</span> => <span class="string">'graduation'</span>
    ])
        -><span class="method">select</span>(<span class="string">'education'</span>, <span class="string">'Höchster Abschluss'</span>, [
            <span class="string">'abitur'</span> => <span class="string">'Abitur'</span>,
            <span class="string">'bachelor'</span> => <span class="string">'Bachelor'</span>,
            <span class="string">'master'</span> => <span class="string">'Master'</span>,
            <span class="string">'phd'</span> => <span class="string">'Promotion'</span>
        ], [<span class="string">'required'</span> => <span class="keyword">true</span>])
        
        -><span class="method">range</span>(<span class="string">'experience'</span>, <span class="string">'Jahre Berufserfahrung'</span>, [
            <span class="string">'min'</span> => <span class="string">0</span>,
            <span class="string">'max'</span> => <span class="string">40</span>,
            <span class="string">'value'</span> => <span class="string">5</span>,
            <span class="string">'unit'</span> => <span class="string">'Jahre'</span>
        ])
        
        -><span class="method">textarea</span>(<span class="string">'skills'</span>, <span class="string">'Ihre Fähigkeiten'</span>, [
            <span class="string">'rows'</span> => <span class="string">4</span>,
            <span class="string">'placeholder'</span> => <span class="string">'Beschreiben Sie Ihre wichtigsten Fähigkeiten...'</span>
        ])
    -><span class="method">endGroup</span>()
    
    <span class="comment">// Submit Buttons</span>
    -><span class="method">divider</span>()
    -><span class="method">checkbox</span>(<span class="string">'terms'</span>, <span class="string">'Ich akzeptiere die Datenschutzbestimmungen'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>
    ])
    -><span class="method">submit</span>(<span class="string">'Bewerbung absenden'</span>, [
        <span class="string">'icon'</span> => <span class="string">'send'</span>, 
        <span class="string">'class'</span> => <span class="string">'primary large'</span>
    ])
    -><span class="method">button</span>(<span class="string">'Zurücksetzen'</span>, [
        <span class="string">'type'</span> => <span class="string">'reset'</span>, 
        <span class="string">'class'</span> => <span class="string">'secondary'</span>
    ]);

<span class="variable">$form</span>-><span class="method">display</span>();</pre>
            </div>
        </div>
    </div>
    
    <script>
    // WICHTIG: Warten bis alles geladen ist
    window.addEventListener('load', function() {
        console.log('=== DEBUGGING SUBMIT PROBLEM ===');
        
        // Überprüfe ob jQuery geladen ist
        if (typeof jQuery === 'undefined') {
            alert('FEHLER: jQuery ist nicht geladen!');
            return;
        }
        
        // Finde das Formular
        var form = document.getElementById('application_form');
        if (!form) {
            alert('FEHLER: Formular nicht gefunden!');
            return;
        }
        
        console.log('Form gefunden:', form);
        
        // Option 1: Vanilla JavaScript Submit Handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('FORM SUBMIT DETECTED (Vanilla JS)');
            alert('Formular wurde abgesendet! (Vanilla JS Handler)');
            
            // AJAX mit jQuery
            var formData = new FormData(form);
            
            // Debug: Zeige was gesendet wird
            console.log('=== Sending form data ===');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // KEIN Debug-Flag mehr - wir wollen echte Validierung sehen
            // formData.append('debug', '1');
            
            jQuery.ajax({
                url: 'process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if(response.success) {
                        jQuery('#result').html('<div class="ui success message"><i class="check icon"></i> ' + response.message + '</div>');
                        if(response.data && response.data.application_id) {
                            jQuery('#result').append('<div class="ui info message">Bewerbungsnummer: <strong>' + response.data.application_id + '</strong></div>');
                        }
                    } else {
                        jQuery('#result').html('<div class="ui error message"><i class="times icon"></i> ' + response.message + '</div>');
                        if (response.errors) {
                            console.log('Validation errors:', response.errors);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    
                    // Erst alle Felder zurücksetzen
                    jQuery('#application_form .field').removeClass('error');
                    jQuery('#application_form .error.message').remove();
                    
                    // Versuche Response zu parsen
                    var errorMsg = 'Fehler: ' + error;
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMsg = response.message;
                        }
                        
                        // Markiere fehlerhafte Felder ROT
                        if (response.errors) {
                            console.log('Validation errors:', response.errors);
                            
                            for (var fieldName in response.errors) {
                                // Finde das Feld und markiere es rot
                                var field = jQuery('[name="' + fieldName + '"]');
                                if (field.length > 0) {
                                    field.closest('.field').addClass('error');
                                    
                                    // Füge Fehlermeldung unter dem Feld hinzu
                                    var errorText = '<div class="ui pointing red basic label">' + response.errors[fieldName] + '</div>';
                                    field.after(errorText);
                                    
                                    // Wenn in Tab, aktiviere den Tab
                                    var tabContent = field.closest('.tab.segment');
                                    if (tabContent.length > 0) {
                                        var tabId = tabContent.attr('data-tab');
                                        // Aktiviere den Tab mit dem Fehler
                                        jQuery('.tabular.menu .item[data-tab="' + tabId + '"]').click();
                                    }
                                }
                            }
                            
                            // Zeige Zusammenfassung oben
                            var errorList = '<ul class="list">';
                            for (var field in response.errors) {
                                errorList += '<li>' + response.errors[field] + '</li>';
                            }
                            errorList += '</ul>';
                            
                            jQuery('#result').html(
                                '<div class="ui error message">' +
                                '<div class="header">Bitte korrigieren Sie folgende Fehler:</div>' +
                                errorList +
                                '</div>'
                            );
                        } else {
                            jQuery('#result').html('<div class="ui error message"><i class="times icon"></i> ' + errorMsg + '</div>');
                        }
                        
                        // Debug-Info anzeigen wenn vorhanden
                        if (response.debug_info) {
                            console.log('Debug Info:', response.debug_info);
                        }
                    } catch(e) {
                        console.log('Could not parse error response:', e);
                        jQuery('#result').html('<div class="ui error message"><i class="times icon"></i> ' + errorMsg + '</div>');
                    }
                }
            });
            
            return false;
        });
        
        // Option 2: jQuery Handler als Backup
        jQuery(function($) {
            console.log('jQuery ready, adding FORCE SUBMIT handler...');
            
            // WICHTIG: Button klick abfangen (auch wenn Icon geklickt wird)
            $(document).on('click', '#application_form button[type="submit"], #application_form button[type="submit"] *', function(e) {
                // Finde den Button (falls Icon geklickt wurde)
                var button = $(e.target).closest('button[type="submit"]');
                if (!button.length) return;
                
                console.log('SUBMIT BUTTON CLICKED - FORCING SUBMIT!');
                e.preventDefault();
                e.stopPropagation();
                
                // Erst Client-seitige Validierung
                var hasErrors = false;
                var errors = {};
                
                // Prüfe Pflichtfelder
                $('#application_form [required]').each(function() {
                    var field = $(this);
                    var value = field.val();
                    var name = field.attr('name');
                    
                    // Checkbox special handling
                    if (field.attr('type') === 'checkbox') {
                        if (!field.is(':checked')) {
                            errors[name] = 'Muss akzeptiert werden';
                            hasErrors = true;
                        }
                    } else if (!value || value.trim() === '') {
                        var label = field.closest('.field').find('label').first().text();
                        errors[name] = label + ' ist erforderlich';
                        hasErrors = true;
                    }
                });
                
                // Zeige Client-seitige Fehler
                if (hasErrors) {
                    console.log('Client-side validation failed:', errors);
                    
                    // Markiere Felder rot
                    $('.field').removeClass('error');
                    $('.ui.pointing.red.basic.label').remove();
                    
                    for (var fieldName in errors) {
                        var field = $('[name="' + fieldName + '"]');
                        field.closest('.field').addClass('error');
                        field.after('<div class="ui pointing red basic label">' + errors[fieldName] + '</div>');
                        
                        // Aktiviere Tab mit Fehler
                        var tab = field.closest('.tab.segment');
                        if (tab.length && !tab.hasClass('active')) {
                            var tabId = tab.attr('data-tab');
                            $('.tabular.menu .item[data-tab="' + tabId + '"]').click();
                            break; // Nur ersten Tab mit Fehler öffnen
                        }
                    }
                    
                    $('#result').html(
                        '<div class="ui error message">' +
                        '<div class="header">Bitte füllen Sie alle Pflichtfelder aus!</div>' +
                        '</div>'
                    );
                    
                    return false;
                }
                
                // Form manuell submitten wenn alles OK
                var form = document.getElementById('application_form');
                if (form) {
                    // Submit Event manuell auslösen
                    var submitEvent = new Event('submit', { 
                        bubbles: true, 
                        cancelable: true 
                    });
                    
                    // Event dispatchen - das triggert unseren Submit-Handler
                    var cancelled = !form.dispatchEvent(submitEvent);
                    console.log('Submit event dispatched, cancelled:', cancelled);
                }
                
                return false;
            });
            
            // Live-Validierung: Entferne Fehler wenn Feld ausgefüllt wird
            $('#application_form input, #application_form select, #application_form textarea').on('change blur', function() {
                var field = $(this);
                var value = field.val();
                
                // Checkbox special handling
                if (field.attr('type') === 'checkbox') {
                    if (field.is(':checked')) {
                        field.closest('.field').removeClass('error');
                        field.siblings('.ui.pointing.red.basic.label').remove();
                    }
                } else if (value && value.trim() !== '') {
                    field.closest('.field').removeClass('error');
                    field.siblings('.ui.pointing.red.basic.label').remove();
                }
            });
        });
        
        console.log('=== HANDLERS ATTACHED ===');
    });
    </script>
</body>
</html>