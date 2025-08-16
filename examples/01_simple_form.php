<?php
/**
 * EasyForm Beispiel 1: Einfaches Kontaktformular
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyForm;

// Neues Formular erstellen
$form = new EasyForm('contact_form', [
    'width' => 600,
    'language' => 'de'
]);

// Formular konfigurieren mit Fluent Interface und AJAX
$form->action('process.php')
    ->method('POST')
    ->ajax([
        'success' => 'function(response) { 
            if(response.success) {
                $("#result").html("<div class=\"ui success message\"><i class=\"check icon\"></i> " + response.message + "</div>");
                $("#contact_form")[0].reset();
            } else {
                $("#result").html("<div class=\"ui error message\"><i class=\"times icon\"></i> " + response.message + "</div>");
            }
        }',
        'error' => 'function(xhr) { 
            $("#result").html("<div class=\"ui error message\"><i class=\"times icon\"></i> Fehler beim Senden des Formulars!</div>");
        }',
        'beforeSend' => '$("#submit_btn").addClass("loading")',
        'complete' => '$("#submit_btn").removeClass("loading")'
    ])
    ->html('<input type="hidden" name="form_id" value="contact_form">')
    ->heading('Kontaktformular', 2)
    ->text('name', 'Ihr Name', [
        'placeholder' => 'Max Mustermann',
        'required' => true,
        'icon' => 'user'
    ])
    ->email('email', 'E-Mail-Adresse', [
        'placeholder' => 'ihre@email.de',
        'required' => true,
        'icon' => 'mail'
    ])
    ->tel('phone', 'Telefonnummer', [
        'placeholder' => '+49 123 456789',
        'icon' => 'phone'
    ])
    ->select('subject', 'Betreff', [
        'general' => 'Allgemeine Anfrage',
        'support' => 'Support',
        'sales' => 'Vertrieb',
        'feedback' => 'Feedback'
    ], [
        'required' => true
    ])
    ->textarea('message', 'Ihre Nachricht', [
        'placeholder' => 'Ihre Nachricht...',
        'rows' => 6,
        'required' => true
    ])
    ->checkbox('newsletter', 'Newsletter abonnieren', [
        'checked' => true
    ])
    ->submit('Nachricht senden', ['icon' => 'send', 'id' => 'submit_btn'])
    ->reset('Zurücksetzen');

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Einfaches Kontaktformular</title>
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
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        @media (max-width: 968px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        .form-card, .code-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .card-header h2 {
            font-size: 1.3rem;
            color: #2d3748;
        }
        .card-header i {
            color: #667eea;
            font-size: 1.5rem;
        }
        .code-block {
            background: #1a202c;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .code-block .comment { color: #718096; }
        .code-block .keyword { color: #ed64a6; }
        .code-block .string { color: #68d391; }
        .code-block .variable { color: #63b3ed; }
        .code-block .method { color: #fbb040; }
        .usage-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .usage-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .usage-tab {
            padding: 10px 20px;
            background: none;
            border: none;
            color: #718096;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        .usage-tab.active {
            color: #667eea;
        }
        .usage-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #667eea;
        }
        .usage-content {
            display: none;
        }
        .usage-content.active {
            display: block;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            align-items: start;
            gap: 10px;
        }
        .feature-item i {
            color: #48bb78;
            margin-top: 3px;
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
    </style>
</head>
<body>
    <!-- jQuery und Semantic UI VOR dem Formular laden -->
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <div class="container">
        <!-- Header -->
        <div class="header-section">
            <h1>Einfaches Kontaktformular</h1>
            <p>Grundlegendes Beispiel mit den wichtigsten Feldtypen und AJAX-Submit</p>
            <a href="index.php" class="back-button">
                <i class="arrow left icon"></i> Zurück zur Übersicht
            </a>
        </div>
        
        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Form Display -->
            <div class="form-card">
                <div class="card-header">
                    <i class="wpforms icon"></i>
                    <h2>Live Demo</h2>
                </div>
                <div id="result"></div>
                <?php $form->display(); ?>
            </div>
            
            <!-- Code Example -->
            <div class="code-card">
                <div class="card-header">
                    <i class="code icon"></i>
                    <h2>PHP Code</h2>
                </div>
                <div class="code-block">
<pre><span class="comment">// EasyForm initialisieren</span>
<span class="keyword">use</span> EasyForm\EasyForm;

<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'contact_form'</span>, [
    <span class="string">'width'</span> => <span class="string">600</span>,
    <span class="string">'language'</span> => <span class="string">'de'</span>
]);

<span class="comment">// Formular mit AJAX konfigurieren</span>
<span class="variable">$form</span>-><span class="method">action</span>(<span class="string">'process.php'</span>)
    -><span class="method">method</span>(<span class="string">'POST'</span>)
    -><span class="method">ajax</span>([
        <span class="string">'success'</span> => <span class="string">'function(response) { ... }'</span>,
        <span class="string">'error'</span> => <span class="string">'function(xhr) { ... }'</span>
    ])
    
    <span class="comment">// Formularfelder hinzufügen</span>
    -><span class="method">heading</span>(<span class="string">'Kontaktformular'</span>, <span class="string">2</span>)
    
    -><span class="method">text</span>(<span class="string">'name'</span>, <span class="string">'Ihr Name'</span>, [
        <span class="string">'placeholder'</span> => <span class="string">'Max Mustermann'</span>,
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'icon'</span> => <span class="string">'user'</span>
    ])
    
    -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail-Adresse'</span>, [
        <span class="string">'placeholder'</span> => <span class="string">'ihre@email.de'</span>,
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'icon'</span> => <span class="string">'mail'</span>
    ])
    
    -><span class="method">tel</span>(<span class="string">'phone'</span>, <span class="string">'Telefonnummer'</span>, [
        <span class="string">'placeholder'</span> => <span class="string">'+49 123 456789'</span>,
        <span class="string">'icon'</span> => <span class="string">'phone'</span>
    ])
    
    -><span class="method">select</span>(<span class="string">'subject'</span>, <span class="string">'Betreff'</span>, [
        <span class="string">'general'</span> => <span class="string">'Allgemeine Anfrage'</span>,
        <span class="string">'support'</span> => <span class="string">'Support'</span>,
        <span class="string">'sales'</span> => <span class="string">'Vertrieb'</span>,
        <span class="string">'feedback'</span> => <span class="string">'Feedback'</span>
    ], [<span class="string">'required'</span> => <span class="keyword">true</span>])
    
    -><span class="method">textarea</span>(<span class="string">'message'</span>, <span class="string">'Ihre Nachricht'</span>, [
        <span class="string">'placeholder'</span> => <span class="string">'Ihre Nachricht...'</span>,
        <span class="string">'rows'</span> => <span class="string">6</span>,
        <span class="string">'required'</span> => <span class="keyword">true</span>
    ])
    
    -><span class="method">checkbox</span>(<span class="string">'newsletter'</span>, <span class="string">'Newsletter abonnieren'</span>, [
        <span class="string">'checked'</span> => <span class="keyword">true</span>
    ])
    
    -><span class="method">submit</span>(<span class="string">'Nachricht senden'</span>, [
        <span class="string">'icon'</span> => <span class="string">'send'</span>
    ])
    -><span class="method">reset</span>(<span class="string">'Zurücksetzen'</span>);</pre>
                </div>
            </div>
        </div>
        
        <!-- Usage Examples -->
        <div class="usage-section">
            <div class="card-header">
                <i class="book icon"></i>
                <h2>Verwendung & Integration</h2>
            </div>
            
            <div class="usage-tabs">
                <button class="usage-tab active" data-tab="basic">Basis Setup</button>
                <button class="usage-tab" data-tab="ajax">AJAX Handler</button>
                <button class="usage-tab" data-tab="validation">Validierung</button>
                <button class="usage-tab" data-tab="styling">Styling</button>
            </div>
            
            <div class="usage-content active" id="basic">
                <h3>1. Installation</h3>
                <div class="code-block">
<pre><span class="comment">// Autoloader einbinden</span>
<span class="keyword">require_once</span> <span class="string">'../autoload.php'</span>;
<span class="keyword">use</span> EasyForm\EasyForm;

<span class="comment">// Neues Formular erstellen</span>
<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'my_form'</span>);

<span class="comment">// Formular anzeigen</span>
<span class="variable">$form</span>-><span class="method">display</span>();</pre>
                </div>
                
                <h3>2. Verfügbare Feldtypen</h3>
                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>text()</strong> - Textfeld
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>email()</strong> - E-Mail Feld
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>password()</strong> - Passwort
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>number()</strong> - Zahlenfeld
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>tel()</strong> - Telefonnummer
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>url()</strong> - URL/Website
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>date()</strong> - Datum
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>textarea()</strong> - Mehrzeiliger Text
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>select()</strong> - Dropdown
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>checkbox()</strong> - Checkbox
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>radio()</strong> - Radio Buttons
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="check icon"></i>
                        <div>
                            <strong>range()</strong> - Slider
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="usage-content" id="ajax">
                <h3>AJAX Submit Handler</h3>
                <div class="code-block">
<pre><span class="comment">// process.php - Server-side Handler</span>
<span class="keyword">session_start</span>();

<span class="comment">// CSRF Token prüfen</span>
<span class="keyword">if</span> (<span class="variable">$_POST</span>[<span class="string">'csrf_token'</span>] !== <span class="variable">$_SESSION</span>[<span class="string">'csrf_token'</span>]) {
    <span class="keyword">die</span>(<span class="method">json_encode</span>([<span class="string">'success'</span> => <span class="keyword">false</span>, <span class="string">'message'</span> => <span class="string">'Invalid token'</span>]));
}

<span class="comment">// Formulardaten validieren</span>
<span class="variable">$errors</span> = [];

<span class="keyword">if</span> (<span class="method">empty</span>(<span class="variable">$_POST</span>[<span class="string">'name'</span>])) {
    <span class="variable">$errors</span>[<span class="string">'name'</span>] = <span class="string">'Name ist erforderlich'</span>;
}

<span class="keyword">if</span> (!<span class="method">filter_var</span>(<span class="variable">$_POST</span>[<span class="string">'email'</span>], FILTER_VALIDATE_EMAIL)) {
    <span class="variable">$errors</span>[<span class="string">'email'</span>] = <span class="string">'Ungültige E-Mail-Adresse'</span>;
}

<span class="comment">// Response senden</span>
<span class="keyword">if</span> (<span class="method">count</span>(<span class="variable">$errors</span>) > <span class="string">0</span>) {
    <span class="keyword">echo</span> <span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">false</span>,
        <span class="string">'errors'</span> => <span class="variable">$errors</span>
    ]);
} <span class="keyword">else</span> {
    <span class="comment">// Daten verarbeiten (z.B. in DB speichern)</span>
    <span class="keyword">echo</span> <span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">true</span>,
        <span class="string">'message'</span> => <span class="string">'Formular erfolgreich gesendet!'</span>
    ]);
}</pre>
                </div>
            </div>
            
            <div class="usage-content" id="validation">
                <h3>Client-seitige Validierung</h3>
                <div class="code-block">
<pre><span class="comment">// Validierungsregeln definieren</span>
<span class="variable">$form</span>-><span class="method">rule</span>(<span class="string">'name'</span>, <span class="string">'required|minlength[3]|maxlength[50]'</span>)
     -><span class="method">rule</span>(<span class="string">'email'</span>, <span class="string">'required|email'</span>)
     -><span class="method">rule</span>(<span class="string">'phone'</span>, <span class="string">'phone'</span>)
     -><span class="method">rule</span>(<span class="string">'message'</span>, <span class="string">'required|minlength[10]'</span>);

<span class="comment">// Verfügbare Validierungsregeln:</span>
<span class="comment">// - required: Pflichtfeld</span>
<span class="comment">// - email: Gültige E-Mail-Adresse</span>
<span class="comment">// - url: Gültige URL</span>
<span class="comment">// - number: Nur Zahlen</span>
<span class="comment">// - minlength[n]: Mindestlänge</span>
<span class="comment">// - maxlength[n]: Maximallänge</span>
<span class="comment">// - min[n]: Mindestwert</span>
<span class="comment">// - max[n]: Maximalwert</span>
<span class="comment">// - match[field]: Muss mit anderem Feld übereinstimmen</span></pre>
                </div>
            </div>
            
            <div class="usage-content" id="styling">
                <h3>Anpassung & Styling</h3>
                <div class="code-block">
<pre><span class="comment">// Formular-Optionen</span>
<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'my_form'</span>, [
    <span class="string">'width'</span> => <span class="string">600</span>,                <span class="comment">// Breite in Pixel</span>
    <span class="string">'size'</span> => <span class="string">'large'</span>,            <span class="comment">// tiny, small, large, huge</span>
    <span class="string">'class'</span> => <span class="string">'custom-form'</span>,     <span class="comment">// Zusätzliche CSS-Klasse</span>
    <span class="string">'language'</span> => <span class="string">'de'</span>,           <span class="comment">// Sprache für Meldungen</span>
    <span class="string">'liveValidation'</span> => <span class="keyword">true</span>      <span class="comment">// Live-Validierung aktivieren</span>
]);

<span class="comment">// Feld-Optionen</span>
<span class="variable">$form</span>-><span class="method">text</span>(<span class="string">'field_name'</span>, <span class="string">'Label'</span>, [
    <span class="string">'placeholder'</span> => <span class="string">'Platzhalter'</span>,
    <span class="string">'icon'</span> => <span class="string">'user'</span>,             <span class="comment">// Semantic UI Icon</span>
    <span class="string">'help'</span> => <span class="string">'Hilfetext'</span>,        <span class="comment">// Hilfetext unter dem Feld</span>
    <span class="string">'required'</span> => <span class="keyword">true</span>,            <span class="comment">// Pflichtfeld</span>
    <span class="string">'disabled'</span> => <span class="keyword">false</span>,           <span class="comment">// Feld deaktivieren</span>
    <span class="string">'readonly'</span> => <span class="keyword">false</span>,           <span class="comment">// Nur lesen</span>
    <span class="string">'class'</span> => <span class="string">'custom-field'</span>     <span class="comment">// Zusätzliche CSS-Klasse</span>
]);</pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        // Tab functionality
        $('.usage-tab').on('click', function() {
            // Update active tab
            $('.usage-tab').removeClass('active');
            $(this).addClass('active');
            
            // Show corresponding content
            var tabId = $(this).data('tab');
            $('.usage-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });
        
        // E-Mail Validierungsfunktion
        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Direkter AJAX Submit Handler
        function handleFormSubmit() {
            console.log('Setting up direct submit handler...');
            
            // Entferne alle bestehenden Handler
            $('#contact_form').off('submit');
            $(document).off('click', '#contact_form button[type="submit"]');
            $(document).off('click', '#contact_form button[type="submit"] *');
            
            // Direkter AJAX Handler für das Form
            $('#contact_form').on('submit', function(e) {
                console.log('Form submit triggered!');
                e.preventDefault();
                
                var form = this;
                var formData = new FormData(form);
                
                // Loading state
                $('#submit_btn').addClass('loading');
                $('#result').html('');
                
                console.log('Sending AJAX request...');
                
                $.ajax({
                    url: 'process.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success:', response);
                        if (response.success) {
                            $("#result").html("<div class='ui success message'><i class='check icon'></i> " + response.message + "</div>");
                            form.reset();
                        } else {
                            $("#result").html("<div class='ui error message'><i class='times icon'></i> " + (response.message || 'Fehler beim Senden') + "</div>");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr.responseText);
                        $("#result").html("<div class='ui error message'><i class='times icon'></i> Fehler beim Senden: " + error + "</div>");
                    },
                    complete: function() {
                        $('#submit_btn').removeClass('loading');
                    }
                });
                
                return false;
            });
            
            // Button Click Handler (für Icon-Support)
            $(document).on('click', '#contact_form button[type="submit"]', function(e) {
                console.log('Submit button clicked directly');
                e.preventDefault();
                
                var form = document.getElementById('contact_form');
                if (!form) {
                    console.error('Form not found!');
                    return false;
                }
                
                // Client-seitige Validierung
                var isValid = true;
                var errors = [];
                
                // Prüfe required Felder manuell
                $(form).find('[required]').each(function() {
                    var field = $(this);
                    var value = field.val().trim();
                    var name = field.attr('name');
                    
                    if (!value) {
                        isValid = false;
                        errors.push(name + ' ist erforderlich');
                        field.closest('.field').addClass('error');
                    } else {
                        field.closest('.field').removeClass('error');
                    }
                });
                
                // E-Mail Validierung
                var email = $('[name="email"]').val();
                if (email && !isValidEmail(email)) {
                    isValid = false;
                    errors.push('Bitte geben Sie eine gültige E-Mail-Adresse ein');
                    $('[name="email"]').closest('.field').addClass('error');
                }
                
                if (!isValid) {
                    console.log('Custom validation failed:', errors);
                    $('#result').html("<div class='ui error message'><i class='times icon'></i> Bitte füllen Sie alle Pflichtfelder korrekt aus:<ul><li>" + errors.join('</li><li>') + "</li></ul></div>");
                    return false;
                }
                
                console.log('Validation passed');
                console.log('Triggering form submit...');
                $(form).trigger('submit');
                
                return false;
            });
            
            // Icon Click Handler
            $(document).on('click', '#contact_form button[type="submit"] i', function(e) {
                console.log('Icon clicked, delegating to button');
                e.preventDefault();
                e.stopPropagation();
                
                // Trigger parent button click
                $(this).closest('button[type="submit"]').trigger('click');
                return false;
            });
            
            console.log('Direct handlers attached successfully');
        }
        
        // Setup nach DOM ready
        setTimeout(handleFormSubmit, 500);
    });
    </script>
</body>
</html>