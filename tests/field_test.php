<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyForm;

// Genau das gleiche Formular wie in 02_advanced_form.php
$form = new EasyForm('application_form', [
    'width' => 800,
    'size' => 'large'
]);

$form->action('examples/process.php')
    ->ajax([
        'success' => 'function(response) { 
            console.log("Response:", response);
            if (response.debug) {
                $("#debug").html("<pre>" + JSON.stringify(response.debug, null, 2) + "</pre>");
            }
            $("#result").html("<div class=\"ui success message\">" + response.message + "</div>");
        }',
        'error' => 'function(xhr) { 
            console.error("Error:", xhr);
            try {
                var response = JSON.parse(xhr.responseText);
                $("#result").html("<div class=\"ui error message\">" + response.message + "</div>");
                if (response.errors) {
                    var errorList = "<ul>";
                    for (var field in response.errors) {
                        errorList += "<li><strong>" + field + ":</strong> " + response.errors[field] + "</li>";
                    }
                    errorList += "</ul>";
                    $("#result").append("<div class=\"ui warning message\">" + errorList + "</div>");
                }
            } catch(e) {
                $("#result").html("<div class=\"ui error message\">Error: " + xhr.statusText + "</div>");
            }
        }'
    ])
    ->html('<input type="hidden" name="form_id" value="application_form">')
    ->html('<input type="hidden" name="debug" value="1">')
    ->heading('Test Formular - Zeigt alle gesendeten Daten', 2)
    
    // Nur die Pflichtfelder für den Test
    ->text('firstname', 'Vorname', ['required' => true, 'value' => 'Max'])
    ->text('lastname', 'Nachname', ['required' => true, 'value' => 'Mustermann'])
    ->email('email', 'E-Mail', ['required' => true, 'value' => 'test@example.com'])
    ->select('education', 'Abschluss', [
        'abitur' => 'Abitur',
        'bachelor' => 'Bachelor'
    ], ['required' => true, 'value' => 'abitur'])
    ->checkbox('terms', 'Ich akzeptiere die Datenschutzbestimmungen', ['required' => true, 'checked' => true])
    ->submit('Test Absenden');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Field Test</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
</head>
<body style="padding: 40px;">
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    
    <h1>Field Name Test</h1>
    
    <div id="result"></div>
    <div id="debug" style="background: #f0f0f0; padding: 10px; margin: 20px 0;"></div>
    
    <?php $form->display(); ?>
    
    <hr>
    
    <h3>Erwartete Felder in process.php:</h3>
    <ul>
        <li>firstname (Pflicht)</li>
        <li>lastname (Pflicht)</li>
        <li>email (Pflicht)</li>
        <li>education (Pflicht)</li>
        <li>terms (Pflicht)</li>
    </ul>
    
    <script>
    // Manueller Submit-Handler
    $(document).ready(function() {
        setTimeout(function() {
            $('#application_form').off('submit').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                // Zeige was gesendet wird
                console.log('=== FORM DATA ===');
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
                        console.log('Success:', response);
                        if (response.debug) {
                            $('#debug').html('<h3>Debug Response:</h3><pre>' + JSON.stringify(response.debug, null, 2) + '</pre>');
                        }
                        $('#result').html('<div class="ui success message">' + response.message + '</div>');
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        try {
                            var response = JSON.parse(xhr.responseText);
                            $('#result').html('<div class="ui error message">' + response.message + '</div>');
                            if (response.errors) {
                                var errorHtml = '<div class="ui warning message"><h4>Fehler:</h4><ul>';
                                for (var field in response.errors) {
                                    errorHtml += '<li><strong>' + field + ':</strong> ' + response.errors[field] + '</li>';
                                }
                                errorHtml += '</ul></div>';
                                $('#result').append(errorHtml);
                            }
                        } catch(e) {
                            $('#result').html('<div class="ui error message">Error: ' + xhr.statusText + '</div>');
                        }
                    }
                });
                
                return false;
            });
            
            // Force submit on button click
            $('#application_form button[type="submit"]').on('click', function(e) {
                e.preventDefault();
                $('#application_form').submit();
            });
        }, 100);
    });
    </script>
</body>
</html>