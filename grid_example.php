<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyForm;

$form = new EasyForm('grid_form', [
    'width' => 900,
    'size' => 'large'
]);

$form->action('examples/process.php')
    ->ajax([
        'success' => 'function(response) { 
            alert("Erfolg: " + response.message);
        }'
    ])
    ->html('<input type="hidden" name="form_id" value="grid_test">')
    ->heading('Formular mit Grid-Layout', 2)
    ->html('<p class="ui message info">Dieses Formular zeigt verschiedene Grid-Layouts</p>')
    
    // === 2 Spalten: Vorname und Nachname ===
    ->row(['columns' => 2, 'stackable' => true])
        ->col(8)
            ->text('firstname', 'Vorname', ['required' => true, 'placeholder' => 'Max'])
        ->endCol()
        ->col(8)
            ->text('lastname', 'Nachname', ['required' => true, 'placeholder' => 'Mustermann'])
        ->endCol()
    ->endRow()
    
    // === 3 Spalten: Email, Telefon, Geburtsdatum ===
    ->row(['columns' => 3])
        ->col()
            ->email('email', 'E-Mail', ['required' => true])
        ->endCol()
        ->col()
            ->tel('phone', 'Telefon', ['placeholder' => '+49 123 456789'])
        ->endCol()
        ->col()
            ->date('birthdate', 'Geburtsdatum', ['required' => true])
        ->endCol()
    ->endRow()
    
    // === Ungleiche Spalten: 10 + 6 ===
    ->row()
        ->col(10)
            ->text('street', 'Straße und Hausnummer', ['placeholder' => 'Hauptstraße 123'])
        ->endCol()
        ->col(6)
            ->text('zip', 'PLZ', ['placeholder' => '12345'])
        ->endCol()
    ->endRow()
    
    // === 4 Spalten für kleine Felder ===
    ->row(['columns' => 4])
        ->col()
            ->select('title', 'Anrede', [
                'herr' => 'Herr',
                'frau' => 'Frau',
                'divers' => 'Divers'
            ])
        ->endCol()
        ->col()
            ->select('country', 'Land', [
                'de' => 'Deutschland',
                'at' => 'Österreich',
                'ch' => 'Schweiz'
            ])
        ->endCol()
        ->col()
            ->number('age', 'Alter', ['min' => 18, 'max' => 120])
        ->endCol()
        ->col()
            ->select('language', 'Sprache', [
                'de' => 'Deutsch',
                'en' => 'Englisch',
                'fr' => 'Französisch'
            ])
        ->endCol()
    ->endRow()
    
    // === Responsive Grid: Unterschiedliche Breiten auf verschiedenen Geräten ===
    ->row()
        ->col(16, ['tablet' => 8, 'computer' => 4])
            ->text('field1', 'Mobil: 16, Tablet: 8, Desktop: 4')
        ->endCol()
        ->col(16, ['tablet' => 8, 'computer' => 6])
            ->text('field2', 'Mobil: 16, Tablet: 8, Desktop: 6')
        ->endCol()
        ->col(16, ['tablet' => 16, 'computer' => 6])
            ->text('field3', 'Mobil: 16, Tablet: 16, Desktop: 6')
        ->endCol()
    ->endRow()
    
    // === Volle Breite für Textarea ===
    ->textarea('message', 'Nachricht', [
        'rows' => 4,
        'placeholder' => 'Ihre Nachricht...'
    ])
    
    // === Checkboxen in einer Reihe ===
    ->row(['columns' => 3])
        ->col()
            ->checkbox('newsletter', 'Newsletter abonnieren')
        ->endCol()
        ->col()
            ->checkbox('terms', 'AGB akzeptieren', ['required' => true])
        ->endCol()
        ->col()
            ->checkbox('privacy', 'Datenschutz gelesen')
        ->endCol()
    ->endRow()
    
    ->divider()
    ->submit('Absenden', ['icon' => 'send', 'class' => 'primary large']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Grid Layout Example</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <style>
        body {
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        /* Debug: Zeige Grid-Struktur */
        .show-grid .fields > .field {
            background: rgba(86, 61, 124, 0.05);
            border: 1px solid rgba(86, 61, 124, 0.2);
            border-radius: 4px;
            padding: 5px !important;
        }
        
        /* Fix für Semantic UI fields spacing */
        .ui.form .fields {
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    
    <div class="container">
        <h1 class="ui header">
            <i class="grid layout icon"></i>
            Grid Layout Demo
        </h1>
        
        <div class="ui segment">
            <button class="ui toggle button" id="toggleGrid">
                <i class="eye icon"></i> Grid anzeigen
            </button>
            <button class="ui button" onclick="fillForm()">
                <i class="edit icon"></i> Formular ausfüllen
            </button>
        </div>
        
        <div id="result"></div>
        
        <div id="formContainer">
            <?php $form->display(); ?>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        // Toggle Grid-Ansicht
        $('#toggleGrid').click(function() {
            $('#formContainer').toggleClass('show-grid');
            $(this).toggleClass('active');
        });
        
        // Form submission
        $('#grid_form').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            var formData = new FormData(this);
            
            // Zeige was gesendet wird
            console.log('=== Form Data ===');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            $('#result').html('<div class="ui success message">Formular wurde abgesendet!</div>');
            
            return false;
        });
    });
    
    // Test-Funktion zum Ausfüllen
    function fillForm() {
        $('[name="firstname"]').val('Max');
        $('[name="lastname"]').val('Mustermann');
        $('[name="email"]').val('max@example.com');
        $('[name="phone"]').val('+49 123 456789');
        $('[name="birthdate"]').val('1990-01-01');
        $('[name="street"]').val('Hauptstraße 123');
        $('[name="zip"]').val('12345');
        $('[name="age"]').val('30');
        $('[name="message"]').val('Test Nachricht');
        $('[name="terms"]').prop('checked', true);
    }
    </script>
</body>
</html>