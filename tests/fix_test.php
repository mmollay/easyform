<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyForm;

// Test 1: Simple form
$form1 = new EasyForm('form1');
$form1->text('name', 'Name')->submit('Submit 1');

// Test 2: Form with tabs
$form2 = new EasyForm('form2');
$form2->group('tab1', 'Tab 1', ['type' => 'tab', 'active' => true])
      ->text('field1', 'Field 1')
      ->endGroup()
      ->submit('Submit 2');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Test</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
</head>
<body style="padding: 40px;">
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    
    <h1>Form Structure Test</h1>
    
    <h2>Test 1: Simple Form</h2>
    <div style="border: 2px solid red; padding: 10px;">
        <?php echo $form1->render(); ?>
    </div>
    
    <h2>Test 2: Form with Tabs</h2>
    <div style="border: 2px solid blue; padding: 10px;">
        <?php echo $form2->render(); ?>
    </div>
    
    <h2>HTML Source:</h2>
    <h3>Form 1:</h3>
    <pre style="background: #f0f0f0; padding: 10px; overflow-x: auto;"><?php echo htmlspecialchars($form1->render()); ?></pre>
    
    <h3>Form 2:</h3>
    <pre style="background: #f0f0f0; padding: 10px; overflow-x: auto;"><?php echo htmlspecialchars($form2->render()); ?></pre>
    
    <script>
    $(document).ready(function() {
        // Direkt Submit-Handler hinzufügen
        $('form').on('submit', function(e) {
            e.preventDefault();
            alert('Form ' + this.id + ' submitted!');
            return false;
        });
        
        // Button Click Handler mit manuelem Submit
        $('button[type="submit"]').on('click', function(e) {
            console.log('Button clicked in form:', $(this).closest('form').attr('id'));
            
            // Check if button is inside form
            var form = $(this).closest('form');
            if (form.length === 0) {
                alert('ERROR: Button is NOT inside a form tag!');
                console.error('Button not in form!', this);
            }
        });
    });
    </script>
</body>
</html>