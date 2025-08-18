<?php
// Beispiel: So verwendest du ein generiertes Formular

// 1. Autoloader einbinden
require_once 'autoload.php';

// 2. EasyForm Klasse verwenden
use EasyForm\EasyForm;

// 3. Formular erstellen (dieser Code kommt aus dem Form Builder)
$form = new EasyForm('kontakt_form', [
    'width' => 600,
    'theme' => 'semantic',
    'language' => 'de'
]);

// 4. Formular-Eigenschaften setzen
$form->action('verarbeitung.php')  // Wohin das Formular gesendet wird
     ->method('POST');              // HTTP-Methode

// 5. Felder hinzufügen
$form->text('name', 'Ihr Name', [
    'placeholder' => 'Max Mustermann',
    'required' => true,
    'icon' => 'user'
]);

$form->email('email', 'E-Mail Adresse', [
    'placeholder' => 'ihre@email.de',
    'required' => true,
    'icon' => 'mail'
]);

$form->textarea('nachricht', 'Ihre Nachricht', [
    'placeholder' => 'Schreiben Sie uns...',
    'rows' => 5,
    'required' => true
]);

// 6. Submit-Button
$form->submit('Absenden', [
    'icon' => 'paper plane',
    'class' => 'primary'
]);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kontaktformular</title>
    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <style>
        body {
            padding: 50px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .ui.segment {
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="ui header">
            <i class="envelope icon"></i>
            Kontaktformular
        </h1>
        
        <div class="ui segment">
            <?php 
            // 7. Formular anzeigen
            $form->display(); 
            ?>
        </div>
        
        <div class="ui info message">
            <div class="header">Hinweis</div>
            <p>Dies ist ein Beispielformular erstellt mit EasyForm.</p>
        </div>
    </div>

    <!-- jQuery und Semantic UI JavaScript -->
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
</body>
</html>