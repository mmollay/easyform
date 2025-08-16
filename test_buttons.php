<?php
require_once 'autoload.php';
use EasyForm\EasyForm;

$form = new EasyForm('test_form', [
    'width' => 600,
    'theme' => 'semantic',
    'showErrors' => true,
    'liveValidation' => true,
    'submitButton' => true,
    'resetButton' => true,
    'language' => 'de'
]);

$form->action('process.php')
     ->method('POST');

// Add some fields
$form->text('name', 'Name', [
    'placeholder' => 'Ihr Name',
    'required' => true,
    'icon' => 'user'
]);

$form->email('email', 'E-Mail', [
    'placeholder' => 'ihre@email.de',
    'required' => true,
    'icon' => 'mail'
]);

$form->textarea('message', 'Nachricht', [
    'placeholder' => 'Ihre Nachricht...',
    'rows' => 5
]);

// Add submit and reset buttons
$form->submit('Absenden', [
    'icon' => 'paper plane',
    'class' => 'primary'
]);

$form->reset('Zurücksetzen', [
    'icon' => 'undo',
    'class' => 'secondary'
]);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Test</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
</head>
<body>
    <div class="ui container" style="padding: 40px;">
        <h1>Button Funktionalität Test</h1>
        <div class="ui segment">
            <?php $form->display(); ?>
        </div>
        
        <div class="ui message info">
            <div class="header">Test-Information</div>
            <ul>
                <li>Der "Absenden" Button sollte das Formular senden (zu process.php)</li>
                <li>Der "Zurücksetzen" Button sollte alle Felder leeren</li>
                <li>Die Konfiguration kann über den Form Builder angepasst werden</li>
            </ul>
        </div>
    </div>
    
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
            
            // Test submit button
            $('form').on('submit', function(e) {
                e.preventDefault();
                alert('Submit Button funktioniert! Form würde zu ' + $(this).attr('action') + ' gesendet.');
                return false;
            });
            
            // Test reset button
            $('button[type="reset"]').on('click', function() {
                setTimeout(function() {
                    alert('Reset Button funktioniert! Alle Felder wurden zurückgesetzt.');
                }, 100);
            });
        });
    </script>
</body>
</html>