<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyForm;

$form = new EasyForm('final_test');
$form->action('examples/process.php')
    ->ajax([
        'success' => 'function(response) { 
            console.log("SUCCESS!", response);
            alert("ERFOLG: " + response.message);
        }',
        'error' => 'function(xhr) { 
            console.error("ERROR!", xhr);
            alert("FEHLER: " + xhr.statusText);
        }'
    ])
    ->html('<input type="hidden" name="form_id" value="final_test">')
    ->heading('FINAL TEST - SUBMIT MUSS FUNKTIONIEREN', 2)
    ->text('name', 'Name', ['required' => true])
    ->submit('JETZT ABSENDEN');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Final Test</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        body { 
            padding: 40px; 
            background: #ff0000;
            background: linear-gradient(45deg, #ff0000, #ffff00);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- WICHTIG: jQuery VOR dem Formular laden! -->
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    
    <div class="container">
        <h1 style="color: red;">⚠️ FINAL TEST ⚠�</h1>
        
        <?php $form->display(); ?>
        
        <hr>
        
        <h3>Debug Console:</h3>
        <pre id="console" style="background: #000; color: #0f0; padding: 10px; height: 200px; overflow-y: auto;"></pre>
    </div>
    
    <script>
    // Custom console output
    var debugConsole = document.getElementById('console');
    var originalLog = console.log;
    console.log = function() {
        var args = Array.prototype.slice.call(arguments);
        debugConsole.innerHTML += args.join(' ') + '\n';
        debugConsole.scrollTop = debugConsole.scrollHeight;
        originalLog.apply(console, arguments);
    };
    
    $(document).ready(function() {
        console.log('=== FINAL TEST STARTED ===');
        console.log('jQuery version: ' + $.fn.jquery);
        console.log('Form found: ' + ($('#final_test').length > 0 ? 'YES' : 'NO'));
        console.log('Submit button found: ' + ($('#final_test button[type="submit"]').length > 0 ? 'YES' : 'NO'));
        
        setTimeout(function() {
            console.log('Init function exists: ' + (typeof initEasyForm_final_test === 'function' ? 'YES' : 'NO'));
            
            var form = $('#final_test')[0];
            if (form) {
                var events = $._data(form, 'events');
                console.log('Submit handlers attached: ' + (events && events.submit ? events.submit.length : 0));
            }
            
            console.log('=== READY TO TEST ===');
            console.log('Fill the Name field and click SUBMIT!');
        }, 500);
    });
    </script>
</body>
</html>