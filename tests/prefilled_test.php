<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyForm;

// Formular mit vorausgefüllten Werten
$form = new EasyForm('application_form');
$form->action('examples/process.php')
    ->html('<input type="hidden" name="form_id" value="application_form">')
    ->heading('Vorausgefülltes Test-Formular', 2)
    ->text('firstname', 'Vorname', ['value' => 'Max', 'required' => true])
    ->text('lastname', 'Nachname', ['value' => 'Mustermann', 'required' => true])  
    ->email('email', 'E-Mail', ['value' => 'test@example.com', 'required' => true])
    ->select('education', 'Abschluss', [
        'abitur' => 'Abitur',
        'bachelor' => 'Bachelor'
    ], ['value' => 'abitur', 'required' => true])
    ->checkbox('terms', 'Datenschutz akzeptiert', ['checked' => true, 'required' => true])
    ->submit('Absenden');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Prefilled Test</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
</head>
<body style="padding: 40px;">
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    
    <h1>Vorausgefüllter Test - Sollte direkt funktionieren!</h1>
    
    <div id="result"></div>
    
    <?php $form->display(); ?>
    
    <script>
    $(document).ready(function() {
        // Submit handler
        $('#application_form').on('submit', function(e) {
            e.preventDefault();
            console.log('Submitting form...');
            
            var formData = new FormData(this);
            
            // Log what we're sending
            console.log('=== SENDING ===');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            $.ajax({
                url: 'examples/process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('SUCCESS:', response);
                    if (response.success) {
                        $('#result').html('<div class="ui success message"><i class="check icon"></i> ' + response.message + '</div>');
                    } else {
                        $('#result').html('<div class="ui error message"><i class="times icon"></i> ' + response.message + '</div>');
                        if (response.errors) {
                            console.log('ERRORS:', response.errors);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('ERROR:', xhr);
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        $('#result').html('<div class="ui error message">' + resp.message + '</div>');
                        if (resp.errors) {
                            console.log('VALIDATION ERRORS:', resp.errors);
                        }
                    } catch(e) {
                        $('#result').html('<div class="ui error message">Error: ' + xhr.statusText + '</div>');
                    }
                }
            });
            
            return false;
        });
    });
    </script>
</body>
</html>