<?php
session_start();
require_once '../autoload.php';
use EasyForm\EasyForm;

// Simple test form without AJAX
$form = new EasyForm('test_form');

$form->heading('Test Form', 2)
     ->text('name', 'Name', ['required' => true])
     ->email('email', 'Email')
     ->submit('Send');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
</head>
<body style="padding: 50px;">
    <h1>Simple Test Form</h1>
    <?php $form->display(); ?>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
</body>
</html>