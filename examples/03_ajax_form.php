<?php
/**
 * EasyForm Beispiel 3: AJAX Formular mit Live-Validierung
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyForm;

$form = new EasyForm('ajax_form', [
    'width' => 600,
    'liveValidation' => true
]);

// Formular mit AJAX und Validierung
$form->action('process.php')
    ->ajax([
        'success' => 'function(response) { 
            if(response.success) {
                $("#result").html("<div class=\"ui success message\"><i class=\"check icon\"></i> " + response.message + "</div>"); 
                $("#ajax_form")[0].reset();
            } else {
                var errorHtml = "<div class=\"ui error message\"><i class=\"times icon\"></i> " + response.message;
                if(response.errors) {
                    errorHtml += "<ul>";
                    $.each(response.errors, function(field, error) {
                        errorHtml += "<li>" + error + "</li>";
                    });
                    errorHtml += "</ul>";
                }
                errorHtml += "</div>";
                $("#result").html(errorHtml);
            }
        }',
        'error' => 'function(xhr) { 
            $("#result").html("<div class=\"ui error message\"><i class=\"times icon\"></i> Fehler beim Senden!</div>"); 
        }',
        'beforeSend' => '$("#submit_btn").addClass("loading")',
        'complete' => '$("#submit_btn").removeClass("loading")'
    ])
    ->html('<input type="hidden" name="form_id" value="ajax_form">')
    ->heading('AJAX Formular mit Validierung', 2)
    
    // Felder mit verschiedenen Validierungsregeln
    ->text('username', 'Benutzername', [
        'required' => true,
        'placeholder' => 'mind. 3 Zeichen',
        'icon' => 'user',
        'help' => 'Der Benutzername muss zwischen 3 und 20 Zeichen lang sein',
        'rules' => ['required', 'minlength[3]', 'maxlength[20]']
    ])
    
    ->email('email', 'E-Mail-Adresse', [
        'required' => true,
        'placeholder' => 'ihre@email.de',
        'icon' => 'mail',
        'rules' => ['required', 'email']
    ])
    
    ->password('password', 'Passwort', [
        'required' => true,
        'placeholder' => 'mind. 8 Zeichen',
        'icon' => 'lock',
        'help' => 'Mindestens 8 Zeichen, ein Großbuchstabe und eine Zahl',
        'rules' => ['required', 'minlength[8]']
    ])
    
    ->password('password_confirm', 'Passwort bestätigen', [
        'required' => true,
        'placeholder' => 'Passwort wiederholen',
        'icon' => 'lock',
        'rules' => ['required', 'match[password]']
    ])
    
    ->number('age', 'Alter', [
        'min' => 18,
        'max' => 120,
        'placeholder' => '18-120',
        'icon' => 'calendar',
        'rules' => ['required', 'number', 'min[18]', 'max[120]']
    ])
    
    ->url('website', 'Website (optional)', [
        'placeholder' => 'https://ihre-website.de',
        'icon' => 'globe',
        'rules' => ['url']
    ])
    
    ->divider('Benachrichtigungen')
    
    ->radio('notifications', 'Benachrichtigungen', [
        'all' => 'Alle Benachrichtigungen',
        'important' => 'Nur wichtige',
        'none' => 'Keine'
    ], [
        'value' => 'important',
        'inline' => true
    ])
    
    ->checkbox('newsletter', 'Newsletter abonnieren', [
        'toggle' => true
    ])
    
    ->submit('Registrieren', ['icon' => 'user plus', 'id' => 'submit_btn']);

// Validierungsregeln definieren
$form->rule('username', 'required|minlength[3]|maxlength[20]')
    ->rule('email', 'required|email')
    ->rule('password', 'required|minlength[8]')
    ->rule('age', 'required|number|min[18]|max[120]');

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - AJAX & Validierung</title>
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
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .validation-demo {
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
        .validation-rules {
            background: #f7fafc;
            border-radius: 10px;
            padding: 20px;
        }
        .validation-rules h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .rule-list {
            list-style: none;
            padding: 0;
        }
        .rule-list li {
            padding: 8px 0;
            color: #4a5568;
            display: flex;
            align-items: start;
            gap: 10px;
        }
        .rule-list i {
            color: #48bb78;
            margin-top: 3px;
        }
        .rule-list code {
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #667eea;
        }
        .code-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        .code-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .code-tab {
            padding: 10px 20px;
            background: none;
            border: none;
            color: #718096;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        .code-tab.active {
            color: #667eea;
        }
        .code-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #667eea;
        }
        .code-content {
            display: none;
        }
        .code-content.active {
            display: block;
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
        .feature-card {
            background: #f7fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
            <h1>AJAX & Live-Validierung</h1>
            <p>Asynchrone Formularübertragung mit Echtzeit-Validierung und Feedback</p>
            <a href="index.php" class="back-button">
                <i class="arrow left icon"></i> Zurück zur Übersicht
            </a>
        </div>
        
        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Form Container -->
            <div class="form-container">
                <div class="card-header">
                    <i class="bolt icon"></i>
                    <h2>Live Demo</h2>
                </div>
                <div id="result"></div>
                <?php $form->display(); ?>
            </div>
            
            <!-- Validation Rules -->
            <div class="validation-demo">
                <div class="card-header">
                    <i class="shield icon"></i>
                    <h2>Validierungsregeln</h2>
                </div>
                
                <div class="validation-rules">
                    <h3>Angewendete Regeln:</h3>
                    <ul class="rule-list">
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>Benutzername:</strong> <code>required</code>, <code>minlength[3]</code>, <code>maxlength[20]</code>
                            </div>
                        </li>
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>E-Mail:</strong> <code>required</code>, <code>email</code>
                            </div>
                        </li>
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>Passwort:</strong> <code>required</code>, <code>minlength[8]</code>
                            </div>
                        </li>
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>Passwort bestätigen:</strong> <code>required</code>, <code>match[password]</code>
                            </div>
                        </li>
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>Alter:</strong> <code>required</code>, <code>number</code>, <code>min[18]</code>, <code>max[120]</code>
                            </div>
                        </li>
                        <li>
                            <i class="check icon"></i>
                            <div>
                                <strong>Website:</strong> <code>url</code> (optional)
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <h4><i class="magic icon"></i> Live-Validierung Features</h4>
                    <ul class="rule-list">
                        <li><i class="angle right icon"></i> Validierung bei Eingabe</li>
                        <li><i class="angle right icon"></i> Sofortiges visuelles Feedback</li>
                        <li><i class="angle right icon"></i> Fehlermeldungen unter Feldern</li>
                        <li><i class="angle right icon"></i> Loading-Status beim Submit</li>
                        <li><i class="angle right icon"></i> AJAX ohne Seitenreload</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Code Examples -->
        <div class="code-section">
            <div class="card-header">
                <i class="code icon"></i>
                <h2>Implementation</h2>
            </div>
            
            <div class="code-tabs">
                <button class="code-tab active" data-tab="form-code">Formular Code</button>
                <button class="code-tab" data-tab="ajax-code">AJAX Configuration</button>
                <button class="code-tab" data-tab="validation-code">Validierung</button>
                <button class="code-tab" data-tab="handler-code">Server Handler</button>
            </div>
            
            <!-- Form Code -->
            <div class="code-content active" id="form-code">
                <div class="code-block">
<pre><span class="keyword">use</span> EasyForm\EasyForm;

<span class="variable">$form</span> = <span class="keyword">new</span> <span class="method">EasyForm</span>(<span class="string">'ajax_form'</span>, [
    <span class="string">'width'</span> => <span class="string">600</span>,
    <span class="string">'liveValidation'</span> => <span class="keyword">true</span>  <span class="comment">// Live-Validierung aktivieren</span>
]);

<span class="comment">// Felder mit Validierungsregeln</span>
<span class="variable">$form</span>
    -><span class="method">text</span>(<span class="string">'username'</span>, <span class="string">'Benutzername'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'placeholder'</span> => <span class="string">'mind. 3 Zeichen'</span>,
        <span class="string">'icon'</span> => <span class="string">'user'</span>,
        <span class="string">'help'</span> => <span class="string">'Der Benutzername muss zwischen 3 und 20 Zeichen lang sein'</span>,
        <span class="string">'rules'</span> => [<span class="string">'required'</span>, <span class="string">'minlength[3]'</span>, <span class="string">'maxlength[20]'</span>]
    ])
    
    -><span class="method">email</span>(<span class="string">'email'</span>, <span class="string">'E-Mail-Adresse'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'placeholder'</span> => <span class="string">'ihre@email.de'</span>,
        <span class="string">'icon'</span> => <span class="string">'mail'</span>,
        <span class="string">'rules'</span> => [<span class="string">'required'</span>, <span class="string">'email'</span>]
    ])
    
    -><span class="method">password</span>(<span class="string">'password'</span>, <span class="string">'Passwort'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'placeholder'</span> => <span class="string">'mind. 8 Zeichen'</span>,
        <span class="string">'icon'</span> => <span class="string">'lock'</span>,
        <span class="string">'help'</span> => <span class="string">'Mindestens 8 Zeichen, ein Großbuchstabe und eine Zahl'</span>,
        <span class="string">'rules'</span> => [<span class="string">'required'</span>, <span class="string">'minlength[8]'</span>]
    ])
    
    -><span class="method">password</span>(<span class="string">'password_confirm'</span>, <span class="string">'Passwort bestätigen'</span>, [
        <span class="string">'required'</span> => <span class="keyword">true</span>,
        <span class="string">'placeholder'</span> => <span class="string">'Passwort wiederholen'</span>,
        <span class="string">'icon'</span> => <span class="string">'lock'</span>,
        <span class="string">'rules'</span> => [<span class="string">'required'</span>, <span class="string">'match[password]'</span>]
    ]);</pre>
                </div>
            </div>
            
            <!-- AJAX Code -->
            <div class="code-content" id="ajax-code">
                <div class="code-block">
<pre><span class="comment">// AJAX-Konfiguration</span>
<span class="variable">$form</span>-><span class="method">action</span>(<span class="string">'process.php'</span>)
    -><span class="method">ajax</span>([
        <span class="string">'success'</span> => <span class="string">'function(response) { 
            if(response.success) {
                // Success message anzeigen
                $("#result").html(
                    '&lt;div class="ui success message"&gt;' +
                    '&lt;i class="check icon"&gt;&lt;/i&gt; ' + 
                    response.message + 
                    '&lt;/div&gt;'
                ); 
                // Formular zurücksetzen
                $("#ajax_form")[0].reset();
            } else {
                // Error message aufbauen
                var errorHtml = '&lt;div class="ui error message"&gt;' +
                                '&lt;i class="times icon"&gt;&lt;/i&gt; ' + 
                                response.message;
                
                // Einzelne Fehler anzeigen
                if(response.errors) {
                    errorHtml += '&lt;ul&gt;';
                    $.each(response.errors, function(field, error) {
                        errorHtml += '&lt;li&gt;' + error + '&lt;/li&gt;';
                    });
                    errorHtml += '&lt;/ul&gt;';
                }
                errorHtml += '&lt;/div&gt;';
                $("#result").html(errorHtml);
            }
        }'</span>,
        
        <span class="string">'error'</span> => <span class="string">'function(xhr) { 
            // Netzwerkfehler anzeigen
            $("#result").html(
                '&lt;div class="ui error message"&gt;' +
                '&lt;i class="times icon"&gt;&lt;/i&gt; ' +
                'Fehler beim Senden!&lt;/div&gt;'
            ); 
        }'</span>,
        
        <span class="string">'beforeSend'</span> => <span class="string">'$("#submit_btn").addClass("loading")'</span>,
        
        <span class="string">'complete'</span> => <span class="string">'$("#submit_btn").removeClass("loading")'</span>
    ]);</pre>
                </div>
            </div>
            
            <!-- Validation Code -->
            <div class="code-content" id="validation-code">
                <div class="code-block">
<pre><span class="comment">// Validierungsregeln definieren</span>
<span class="variable">$form</span>
    -><span class="method">rule</span>(<span class="string">'username'</span>, <span class="string">'required|minlength[3]|maxlength[20]'</span>)
    -><span class="method">rule</span>(<span class="string">'email'</span>, <span class="string">'required|email'</span>)
    -><span class="method">rule</span>(<span class="string">'password'</span>, <span class="string">'required|minlength[8]'</span>)
    -><span class="method">rule</span>(<span class="string">'age'</span>, <span class="string">'required|number|min[18]|max[120]'</span>);

<span class="comment">// Verfügbare Validierungsregeln:</span>
<span class="comment">// ├─ required       - Pflichtfeld</span>
<span class="comment">// ├─ email         - Gültige E-Mail-Adresse</span>
<span class="comment">// ├─ url           - Gültige URL</span>
<span class="comment">// ├─ number        - Nur Zahlen</span>
<span class="comment">// ├─ alpha         - Nur Buchstaben</span>
<span class="comment">// ├─ alphanumeric  - Buchstaben und Zahlen</span>
<span class="comment">// ├─ minlength[n]  - Mindestlänge</span>
<span class="comment">// ├─ maxlength[n]  - Maximallänge</span>
<span class="comment">// ├─ min[n]        - Mindestwert (für Zahlen)</span>
<span class="comment">// ├─ max[n]        - Maximalwert (für Zahlen)</span>
<span class="comment">// ├─ match[field]  - Muss mit anderem Feld übereinstimmen</span>
<span class="comment">// ├─ pattern[regex]- Regex-Pattern</span>
<span class="comment">// └─ phone        - Telefonnummer</span>

<span class="comment">// Custom Error Messages</span>
<span class="variable">$form</span>-><span class="method">messages</span>([
    <span class="string">'username.required'</span> => <span class="string">'Bitte geben Sie einen Benutzernamen ein'</span>,
    <span class="string">'username.minlength'</span> => <span class="string">'Der Benutzername ist zu kurz'</span>,
    <span class="string">'email.email'</span> => <span class="string">'Bitte geben Sie eine gültige E-Mail-Adresse ein'</span>,
    <span class="string">'password.minlength'</span> => <span class="string">'Das Passwort muss mindestens 8 Zeichen lang sein'</span>
]);</pre>
                </div>
            </div>
            
            <!-- Server Handler Code -->
            <div class="code-content" id="handler-code">
                <div class="code-block">
<pre><span class="comment">// process.php - Server-side Handler</span>
<span class="keyword">header</span>(<span class="string">'Content-Type: application/json'</span>);
<span class="keyword">session_start</span>();

<span class="comment">// CSRF Token prüfen</span>
<span class="keyword">if</span> (!<span class="keyword">isset</span>(<span class="variable">$_POST</span>[<span class="string">'csrf_token'</span>]) || 
    <span class="variable">$_POST</span>[<span class="string">'csrf_token'</span>] !== <span class="variable">$_SESSION</span>[<span class="string">'csrf_token'</span>]) {
    <span class="keyword">http_response_code</span>(<span class="string">403</span>);
    <span class="keyword">echo</span> <span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">false</span>,
        <span class="string">'message'</span> => <span class="string">'CSRF Token ungültig'</span>
    ]);
    <span class="keyword">exit</span>;
}

<span class="comment">// Server-seitige Validierung</span>
<span class="variable">$errors</span> = [];
<span class="variable">$data</span> = <span class="variable">$_POST</span>;

<span class="comment">// Username validieren</span>
<span class="keyword">if</span> (<span class="method">empty</span>(<span class="variable">$data</span>[<span class="string">'username'</span>])) {
    <span class="variable">$errors</span>[<span class="string">'username'</span>] = <span class="string">'Benutzername ist erforderlich'</span>;
} <span class="keyword">elseif</span> (<span class="method">strlen</span>(<span class="variable">$data</span>[<span class="string">'username'</span>]) < <span class="string">3</span>) {
    <span class="variable">$errors</span>[<span class="string">'username'</span>] = <span class="string">'Benutzername muss mindestens 3 Zeichen lang sein'</span>;
} <span class="keyword">elseif</span> (<span class="method">strlen</span>(<span class="variable">$data</span>[<span class="string">'username'</span>]) > <span class="string">20</span>) {
    <span class="variable">$errors</span>[<span class="string">'username'</span>] = <span class="string">'Benutzername darf maximal 20 Zeichen lang sein'</span>;
}

<span class="comment">// Email validieren</span>
<span class="keyword">if</span> (<span class="method">empty</span>(<span class="variable">$data</span>[<span class="string">'email'</span>])) {
    <span class="variable">$errors</span>[<span class="string">'email'</span>] = <span class="string">'E-Mail ist erforderlich'</span>;
} <span class="keyword">elseif</span> (!<span class="method">filter_var</span>(<span class="variable">$data</span>[<span class="string">'email'</span>], FILTER_VALIDATE_EMAIL)) {
    <span class="variable">$errors</span>[<span class="string">'email'</span>] = <span class="string">'Ungültige E-Mail-Adresse'</span>;
}

<span class="comment">// Passwort validieren</span>
<span class="keyword">if</span> (<span class="method">empty</span>(<span class="variable">$data</span>[<span class="string">'password'</span>])) {
    <span class="variable">$errors</span>[<span class="string">'password'</span>] = <span class="string">'Passwort ist erforderlich'</span>;
} <span class="keyword">elseif</span> (<span class="method">strlen</span>(<span class="variable">$data</span>[<span class="string">'password'</span>]) < <span class="string">8</span>) {
    <span class="variable">$errors</span>[<span class="string">'password'</span>] = <span class="string">'Passwort muss mindestens 8 Zeichen lang sein'</span>;
}

<span class="comment">// Passwort Bestätigung</span>
<span class="keyword">if</span> (<span class="variable">$data</span>[<span class="string">'password'</span>] !== <span class="variable">$data</span>[<span class="string">'password_confirm'</span>]) {
    <span class="variable">$errors</span>[<span class="string">'password_confirm'</span>] = <span class="string">'Passwörter stimmen nicht überein'</span>;
}

<span class="comment">// Response senden</span>
<span class="keyword">if</span> (<span class="method">count</span>(<span class="variable">$errors</span>) > <span class="string">0</span>) {
    <span class="keyword">http_response_code</span>(<span class="string">422</span>);
    <span class="keyword">echo</span> <span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">false</span>,
        <span class="string">'message'</span> => <span class="string">'Validierung fehlgeschlagen'</span>,
        <span class="string">'errors'</span> => <span class="variable">$errors</span>
    ]);
} <span class="keyword">else</span> {
    <span class="comment">// Erfolgreiche Verarbeitung</span>
    <span class="comment">// Hier würde normalerweise die Datenbank-Speicherung erfolgen</span>
    
    <span class="keyword">echo</span> <span class="method">json_encode</span>([
        <span class="string">'success'</span> => <span class="keyword">true</span>,
        <span class="string">'message'</span> => <span class="string">'Registrierung erfolgreich!'</span>,
        <span class="string">'data'</span> => [
            <span class="string">'user_id'</span> => <span class="method">uniqid</span>(),
            <span class="string">'username'</span> => <span class="variable">$data</span>[<span class="string">'username'</span>],
            <span class="string">'email'</span> => <span class="variable">$data</span>[<span class="string">'email'</span>]
        ]
    ]);
}</pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        // Tab functionality for code examples
        $('.code-tab').on('click', function() {
            $('.code-tab').removeClass('active');
            $(this).addClass('active');
            
            var tabId = $(this).data('tab');
            $('.code-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });
        
        // Proper AJAX form handler - wait for EasyForm to initialize first
        setTimeout(function() {
            console.log('Setting up AJAX form handler');
            
            // First, call the EasyForm initialization if it exists
            if (typeof initEasyForm_ajax_form === 'function') {
                console.log('Calling EasyForm initialization');
                initEasyForm_ajax_form();
            }
            
            // Then override with our working AJAX handler
            setTimeout(function() {
                // Remove ALL existing handlers
                $('#ajax_form').off('submit');
                $('#ajax_form').off('submit.ajax');
                $('#submit_btn').off('click');
                $(document).off('click', '#ajax_form button[type="submit"]');
                $(document).off('click', '#ajax_form button[type="submit"] *');
                
                // Add our AJAX submit handler
                $('#ajax_form').on('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Form submit intercepted');
                    
                    var form = this;
                    var formData = new FormData(form);
                    
                    // Add loading state
                    $('#submit_btn').addClass('loading');
                    $('#result').html('');
                    
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            console.log('AJAX Success:', response);
                            if(response && response.success) {
                                $("#result").html("<div class='ui success message'><i class='check icon'></i> " + (response.message || 'Erfolgreich gesendet!') + "</div>");
                                $("#ajax_form")[0].reset();
                            } else {
                                var errorHtml = "<div class='ui error message'><i class='times icon'></i> " + (response.message || 'Fehler beim Senden');
                                if(response && response.errors) {
                                    errorHtml += "<ul>";
                                    $.each(response.errors, function(field, error) {
                                        errorHtml += "<li>" + error + "</li>";
                                    });
                                    errorHtml += "</ul>";
                                }
                                errorHtml += "</div>";
                                $("#result").html(errorHtml);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', status, error);
                            console.log('Response:', xhr.responseText);
                            $("#result").html("<div class='ui error message'><i class='times icon'></i> Fehler beim Senden: " + error + "</div>");
                        },
                        complete: function() {
                            $('#submit_btn').removeClass('loading');
                        }
                    });
                    
                    return false;
                });
                
                // Direct button click handler - also handle icon clicks
                $(document).on('click', '#submit_btn, #submit_btn *', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Submit button clicked');
                    
                    var form = $('#ajax_form')[0];
                    if (!form) {
                        console.error('Form not found');
                        return false;
                    }
                    
                    // Check validity
                    var isValid = true;
                    var firstInvalid = null;
                    
                    // Check required fields
                    $(form).find('[required]').each(function() {
                        if (!this.value || this.value.trim() === '') {
                            $(this).closest('.field').addClass('error');
                            if (!firstInvalid) firstInvalid = this;
                            isValid = false;
                        } else {
                            $(this).closest('.field').removeClass('error');
                        }
                    });
                    
                    // Check email fields
                    $(form).find('input[type="email"]').each(function() {
                        if (this.value && !this.checkValidity()) {
                            $(this).closest('.field').addClass('error');
                            if (!firstInvalid) firstInvalid = this;
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        if (firstInvalid) {
                            firstInvalid.reportValidity();
                        }
                        return false;
                    }
                    
                    // Trigger form submit
                    console.log('Triggering form submit');
                    $('#ajax_form').submit();
                    return false;
                });
                
                console.log('AJAX handlers attached successfully');
            }, 500);
        }, 1000);
    });
    </script>
</body>
</html>