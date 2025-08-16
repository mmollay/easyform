<?php
/**
 * EasyForm Demo - Contact Form
 * A simple yet powerful contact form example
 */

// Start session for CSRF protection
session_start();

// Include EasyForm
require_once '../../autoload.php';
use EasyForm\EasyForm;

// Initialize variables
$success = false;
$errors = [];
$formData = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
        'newsletter' => isset($_POST['newsletter'])
    ];
    
    // Basic validation
    if (empty($formData['name'])) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($formData['message'])) {
        $errors['message'] = 'Message is required';
    } elseif (strlen($formData['message']) < 10) {
        $errors['message'] = 'Message must be at least 10 characters long';
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Here you would typically:
        // - Send an email
        // - Save to database
        // - Send to API
        
        // For demo purposes, we'll just simulate success
        $success = true;
        
        // You could send an email like this:
        /*
        $to = 'admin@yoursite.com';
        $subject = 'Contact Form: ' . $formData['subject'];
        $body = "Name: {$formData['name']}\n";
        $body .= "Email: {$formData['email']}\n\n";
        $body .= "Message:\n{$formData['message']}";
        $headers = "From: {$formData['email']}\r\nReply-To: {$formData['email']}";
        
        mail($to, $subject, $body, $headers);
        */
        
        // Clear form data on success
        $formData = [];
    }
}

// Create the form
$form = new EasyForm('contact_form', [
    'theme' => 'semantic',
    'width' => 600,
    'class' => 'ui form',
    'method' => 'POST'
]);

// Build the form
$form->heading('Contact Us', 2, ['class' => 'ui dividing header'])
    ->text('name', 'Your Name', [
        'required' => true,
        'placeholder' => 'Enter your full name',
        'icon' => 'user',
        'value' => $formData['name'] ?? '',
        'class' => isset($errors['name']) ? 'error' : ''
    ])
    ->email('email', 'Email Address', [
        'required' => true,
        'placeholder' => 'your@email.com',
        'icon' => 'envelope',
        'value' => $formData['email'] ?? '',
        'class' => isset($errors['email']) ? 'error' : ''
    ])
    ->select('subject', 'Subject', [
        'general' => 'General Inquiry',
        'support' => 'Technical Support',
        'sales' => 'Sales Question',
        'feedback' => 'Feedback',
        'other' => 'Other'
    ], [
        'required' => true,
        'value' => $formData['subject'] ?? 'general'
    ])
    ->textarea('message', 'Your Message', [
        'required' => true,
        'placeholder' => 'Please describe your inquiry in detail...',
        'rows' => 6,
        'value' => $formData['message'] ?? '',
        'class' => isset($errors['message']) ? 'error' : '',
        'help' => 'Minimum 10 characters required'
    ])
    ->checkbox('newsletter', 'Subscribe to our newsletter for updates', [
        'value' => '1',
        'checked' => $formData['newsletter'] ?? false
    ])
    ->submit('Send Message', [
        'class' => 'ui primary button',
        'icon' => 'send'
    ]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Demo - EasyForm</title>
    <meta name="description" content="Interactive contact form demo using EasyForm. See how easy it is to create professional forms with validation.">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <style>
        .demo-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .demo-panel {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e1e1e1;
        }
        
        .demo-panel h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        
        .code-panel {
            background: #1e1e1e;
            color: #f8f8f2;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .field.error input,
        .field.error textarea {
            border-color: #e0b4b4 !important;
            background: #fff6f6 !important;
        }
        
        .breadcrumb {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
        }
        
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .demo-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .demo-panel {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="../../index.html">EasyForm/EasyList</a>
                </div>
                <nav class="nav">
                    <a href="index.html" class="nav-link">← Back to Demos</a>
                    <a href="../easyform.html" class="nav-link">Documentation</a>
                    <a href="../installation.html" class="nav-link">Installation</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="demo-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="../../index.html">Home</a> / 
            <a href="index.html">Demos</a> / 
            <strong>Contact Form</strong>
        </div>

        <!-- Title and Description -->
        <h1>Contact Form Demo</h1>
        <p style="font-size: 1.1rem; color: #6c757d; margin-bottom: 2rem;">
            This demo shows how to create a professional contact form with validation, error handling, and success feedback using EasyForm. 
            The form includes CSRF protection, input validation, and responsive design.
        </p>

        <div class="demo-grid">
            <!-- Live Form Panel -->
            <div class="demo-panel">
                <h3>🎯 Live Demo</h3>
                <p>Try the form below. All validation is working - submit it to see the results!</p>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        <strong>Success!</strong> Your message has been sent successfully. Thank you for contacting us!
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin: 0.5rem 0 0 1rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php $form->display(); ?>
            </div>

            <!-- Code Panel -->
            <div class="demo-panel code-panel">
                <h3 style="color: #f8f8f2;">💻 PHP Code</h3>
                <pre style="margin: 0; white-space: pre-wrap;"><code><?= htmlspecialchars('<?php
require_once \'autoload.php\';
use EasyForm\EasyForm;

// Create the form
$form = new EasyForm(\'contact_form\', [
    \'theme\' => \'semantic\',
    \'width\' => 600
]);

// Build the form with fluent interface
$form->heading(\'Contact Us\', 2)
    ->text(\'name\', \'Your Name\', [
        \'required\' => true,
        \'placeholder\' => \'Enter your full name\',
        \'icon\' => \'user\'
    ])
    ->email(\'email\', \'Email Address\', [
        \'required\' => true,
        \'placeholder\' => \'your@email.com\',
        \'icon\' => \'envelope\'
    ])
    ->select(\'subject\', \'Subject\', [
        \'general\' => \'General Inquiry\',
        \'support\' => \'Technical Support\',
        \'sales\' => \'Sales Question\',
        \'feedback\' => \'Feedback\',
        \'other\' => \'Other\'
    ], [\'required\' => true])
    ->textarea(\'message\', \'Your Message\', [
        \'required\' => true,
        \'placeholder\' => \'Please describe your inquiry...\',
        \'rows\' => 6,
        \'help\' => \'Minimum 10 characters required\'
    ])
    ->checkbox(\'newsletter\', \'Subscribe to newsletter\')
    ->submit(\'Send Message\', [
        \'class\' => \'ui primary button\',
        \'icon\' => \'send\'
    ]);

// Display the form
$form->display();
?>') ?></code></pre>
            </div>
        </div>

        <!-- Features Highlight -->
        <div style="margin: 3rem 0;">
            <h2>✨ Features Demonstrated</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div class="demo-panel" style="padding: 1.5rem;">
                    <h4>🔒 CSRF Protection</h4>
                    <p>Automatic CSRF token generation and validation for security.</p>
                </div>
                <div class="demo-panel" style="padding: 1.5rem;">
                    <h4>✅ Validation</h4>
                    <p>Client and server-side validation with clear error messages.</p>
                </div>
                <div class="demo-panel" style="padding: 1.5rem;">
                    <h4>📱 Responsive</h4>
                    <p>Works perfectly on desktop, tablet, and mobile devices.</p>
                </div>
                <div class="demo-panel" style="padding: 1.5rem;">
                    <h4>🎨 Themed</h4>
                    <p>Beautiful Semantic UI styling with icons and animations.</p>
                </div>
            </div>
        </div>

        <!-- Processing Example -->
        <div class="demo-panel" style="margin: 2rem 0;">
            <h3>📧 Form Processing Code</h3>
            <p>Here's how you would typically process the form data:</p>
            <pre class="code-block" style="background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto;"><code><?= htmlspecialchars('<?php
if ($_SERVER[\'REQUEST_METHOD\'] === \'POST\') {
    // Get and validate form data
    $name = trim($_POST[\'name\'] ?? \'\');
    $email = trim($_POST[\'email\'] ?? \'\');
    $subject = trim($_POST[\'subject\'] ?? \'\');
    $message = trim($_POST[\'message\'] ?? \'\');
    
    // Validation
    $errors = [];
    if (empty($name)) $errors[\'name\'] = \'Name is required\';
    if (empty($email)) $errors[\'email\'] = \'Email is required\';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[\'email\'] = \'Invalid email address\';
    }
    if (empty($message) || strlen($message) < 10) {
        $errors[\'message\'] = \'Message must be at least 10 characters\';
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Send email
        $to = \'admin@yoursite.com\';
        $emailSubject = \'Contact Form: \' . $subject;
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email";
        
        if (mail($to, $emailSubject, $body, $headers)) {
            $success = true;
            // Optionally save to database
            // saveContactMessage($name, $email, $subject, $message);
        }
    }
}
?>') ?></code></pre>
        </div>

        <!-- Try More Examples -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; text-align: center; margin: 3rem 0;">
            <h3 style="color: white; margin-bottom: 1rem;">🚀 Ready for More?</h3>
            <p style="margin-bottom: 2rem;">Explore more advanced examples and see what else EasyForm can do!</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="ajax-form.php" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">AJAX Form</a>
                <a href="user-registration.php" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">Registration Form</a>
                <a href="multi-step-form.php" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">Multi-Step Form</a>
                <a href="../easyform.html" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">Documentation</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Initialize Semantic UI components
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
            $('.ui.checkbox').checkbox();
        });
    </script>
</body>
</html>