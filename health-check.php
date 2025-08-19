<?php
/**
 * FormWerk Health Check Tool
 * Überprüft die komplette Installation auf Fehler und Probleme
 */

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start health check
$results = [];
$errors = 0;
$warnings = 0;
$success = 0;

// Helper function
function check($test, $name, $type = 'error') {
    global $results, $errors, $warnings, $success;
    
    $result = [
        'name' => $name,
        'status' => $test ? 'success' : $type,
        'message' => $test ? 'OK' : 'Failed'
    ];
    
    if ($test) {
        $success++;
    } elseif ($type === 'error') {
        $errors++;
    } else {
        $warnings++;
    }
    
    $results[] = $result;
    return $test;
}

// 1. PHP Version Check
$phpVersion = phpversion();
check(
    version_compare($phpVersion, '7.4.0', '>='),
    "PHP Version ($phpVersion)",
    'error'
);

// 2. Required PHP Extensions
$requiredExtensions = ['json', 'mbstring', 'session', 'filter'];
foreach ($requiredExtensions as $ext) {
    check(
        extension_loaded($ext),
        "PHP Extension: $ext",
        'error'
    );
}

// 3. Optional PHP Extensions
$optionalExtensions = ['pdo', 'curl', 'gd', 'intl'];
foreach ($optionalExtensions as $ext) {
    check(
        extension_loaded($ext),
        "PHP Extension: $ext (optional)",
        'warning'
    );
}

// 4. Core Files Check
$coreFiles = [
    'EasyForm.php' => 'EasyForm Hauptklasse',
    'src/EasyList.php' => 'EasyList Klasse',
    'autoload.php' => 'Autoloader',
    'form_builder.php' => 'Form Builder',
    'list_generator.php' => 'List Generator',
    'index.php' => 'Hauptseite'
];

foreach ($coreFiles as $file => $name) {
    check(
        file_exists($file),
        "Core File: $name",
        'error'
    );
}

// 5. Directory Permissions
$directories = [
    'assets' => 'Assets Verzeichnis',
    'docs' => 'Dokumentation',
    'examples' => 'Beispiele',
    'semantic/dist' => 'Semantic UI'
];

foreach ($directories as $dir => $name) {
    check(
        is_dir($dir) && is_readable($dir),
        "Directory: $name",
        'error'
    );
}

// 6. CSS/JS Resources
$resources = [
    'semantic/dist/semantic.min.css' => 'Semantic UI CSS',
    'semantic/dist/semantic.min.js' => 'Semantic UI JS',
    'jquery/jquery.min.js' => 'jQuery',
    'js/sortable.min.js' => 'Sortable.js',
    'assets/js/language-switcher.js' => 'Language Switcher'
];

foreach ($resources as $file => $name) {
    check(
        file_exists($file),
        "Resource: $name",
        'warning'
    );
}

// 7. PHP Syntax Check
$phpFiles = glob('*.php');
$phpFiles = array_merge($phpFiles, glob('src/*.php'));
$phpFiles = array_merge($phpFiles, glob('examples/*.php'));

$syntaxErrors = [];
foreach ($phpFiles as $file) {
    $output = shell_exec("php -l $file 2>&1");
    if (strpos($output, 'No syntax errors') === false) {
        $syntaxErrors[] = $file;
    }
}

check(
    empty($syntaxErrors),
    "PHP Syntax Check (" . count($phpFiles) . " files)",
    'error'
);

// 8. Form Builder Functionality
ob_start();
$testForm = null;
try {
    require_once 'autoload.php';
    $testForm = new EasyForm('test');
    $testForm->text('test', 'Test');
    check(true, "Form Builder Functionality", 'error');
} catch (Exception $e) {
    check(false, "Form Builder Functionality: " . $e->getMessage(), 'error');
}
ob_end_clean();

// 9. Security Headers Check
$headers = [
    '.htaccess' => 'Apache Security Config',
    'production.htaccess' => 'Production Config'
];

foreach ($headers as $file => $name) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        check(
            strpos($content, 'X-XSS-Protection') !== false ||
            strpos($content, 'X-Frame-Options') !== false,
            "Security: $name",
            'warning'
        );
    }
}

// 10. Language Files
check(
    file_exists('assets/js/language-switcher.js'),
    "Multilingual Support",
    'warning'
);

// Calculate score
$total = $success + $errors + $warnings;
$score = round(($success / $total) * 100);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormWerk Health Check</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 80px 20px 40px 20px;
        }
        
        .main-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .score-section {
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .score-circle {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            position: relative;
        }
        
        .score-value {
            font-size: 4rem;
            font-weight: bold;
            color: <?php echo $score >= 80 ? '#21ba45' : ($score >= 60 ? '#fbbd08' : '#db2828'); ?>;
        }
        
        .score-label {
            font-size: 1.2rem;
            color: #666;
            margin-top: 10px;
        }
        
        .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .results-section {
            padding: 40px;
        }
        
        .check-item {
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        
        .check-item:hover {
            transform: translateX(5px);
        }
        
        .check-item.success {
            background: #f0fff4;
            border-left: 4px solid #21ba45;
        }
        
        .check-item.error {
            background: #fff6f6;
            border-left: 4px solid #db2828;
        }
        
        .check-item.warning {
            background: #fffaf3;
            border-left: 4px solid #fbbd08;
        }
        
        .check-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .check-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .status-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .status-icon.success {
            background: #21ba45;
        }
        
        .status-icon.error {
            background: #db2828;
        }
        
        .status-icon.warning {
            background: #fbbd08;
        }
        
        .actions {
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
        }
        
        .ui.button {
            margin: 5px;
        }
        
        .system-info {
            padding: 20px 40px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        .info-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 20px;
            }
        }
        
        /* Language Switcher */
        .language-switcher-container {
            position: fixed;
            top: 80px;
            right: 30px;
            z-index: 999;
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    
    <!-- Language Switcher -->
    <div class="language-switcher-container">
        <div class="ui compact selection dropdown" id="language-switcher">
            <input type="hidden" name="language">
            <i class="dropdown icon"></i>
            <div class="default text">
                <i class="de flag"></i> Deutsch
            </div>
            <div class="menu">
                <div class="item" data-value="de">
                    <i class="de flag"></i> Deutsch
                </div>
                <div class="item" data-value="en">
                    <i class="us flag"></i> English
                </div>
            </div>
        </div>
    </div>
    
    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <h1 data-i18n="health.title">🏥 FormWerk Health Check</h1>
            <p data-i18n="health.subtitle">Systemdiagnose und Überprüfung</p>
        </div>
        
        <!-- Score Section -->
        <div class="score-section">
            <div class="score-circle">
                <svg viewBox="0 0 200 200">
                    <circle cx="100" cy="100" r="90" fill="none" stroke="#e0e0e0" stroke-width="20"/>
                    <circle cx="100" cy="100" r="90" fill="none" 
                            stroke="<?php echo $score >= 80 ? '#21ba45' : ($score >= 60 ? '#fbbd08' : '#db2828'); ?>" 
                            stroke-width="20"
                            stroke-dasharray="<?php echo 565 * ($score/100); ?> 565"
                            stroke-linecap="round"
                            transform="rotate(-90 100 100)"/>
                </svg>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <div class="score-value"><?php echo $score; ?>%</div>
                </div>
            </div>
            <div class="score-label">
                <?php
                if ($score >= 90) echo "Exzellent! System läuft perfekt.";
                elseif ($score >= 75) echo "Gut! Kleinere Optimierungen möglich.";
                elseif ($score >= 60) echo "Befriedigend. Einige Probleme sollten behoben werden.";
                else echo "Kritisch! Wichtige Komponenten fehlen.";
                ?>
            </div>
            
            <div class="stats">
                <div class="stat">
                    <div class="stat-value" style="color: #21ba45;"><?php echo $success; ?></div>
                    <div class="stat-label" data-i18n="health.successful">Erfolgreich</div>
                </div>
                <div class="stat">
                    <div class="stat-value" style="color: #fbbd08;"><?php echo $warnings; ?></div>
                    <div class="stat-label" data-i18n="health.warnings">Warnungen</div>
                </div>
                <div class="stat">
                    <div class="stat-value" style="color: #db2828;"><?php echo $errors; ?></div>
                    <div class="stat-label" data-i18n="health.errors">Fehler</div>
                </div>
            </div>
        </div>
        
        <!-- Results Section -->
        <div class="results-section">
            <h2 data-i18n="health.detailedResults">Detaillierte Ergebnisse</h2>
            
            <?php foreach ($results as $result): ?>
            <div class="check-item <?php echo $result['status']; ?>">
                <span class="check-name"><?php echo htmlspecialchars($result['name']); ?></span>
                <div class="check-status">
                    <span class="status-text"><?php echo $result['message']; ?></span>
                    <div class="status-icon <?php echo $result['status']; ?>">
                        <?php
                        if ($result['status'] === 'success') echo '✓';
                        elseif ($result['status'] === 'warning') echo '!';
                        else echo '✗';
                        ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- System Info -->
        <div class="system-info">
            <h3 data-i18n="health.systemInfo">System Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">PHP Version</div>
                    <div class="info-value"><?php echo phpversion(); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Server Software</div>
                    <div class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Document Root</div>
                    <div class="info-value"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Memory Limit</div>
                    <div class="info-value"><?php echo ini_get('memory_limit'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Max Execution Time</div>
                    <div class="info-value"><?php echo ini_get('max_execution_time'); ?>s</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Upload Max Size</div>
                    <div class="info-value"><?php echo ini_get('upload_max_filesize'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="actions">
            <h3 data-i18n="health.nextSteps">Nächste Schritte</h3>
            <?php if ($errors > 0): ?>
            <p>⚠️ Bitte beheben Sie die kritischen Fehler, bevor Sie fortfahren.</p>
            <?php elseif ($warnings > 0): ?>
            <p>💡 Einige Optimierungen könnten die Performance verbessern.</p>
            <?php else: ?>
            <p>✅ Ihr System ist bereit für den produktiven Einsatz!</p>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <a href="index.php" class="ui primary button">
                    <i class="home icon"></i> Zur Startseite
                </a>
                <a href="form_builder.php" class="ui violet button">
                    <i class="paint brush icon"></i> Form Builder
                </a>
                <a href="list_generator.php" class="ui purple button">
                    <i class="table icon"></i> List Generator
                </a>
                <a href="docs/" class="ui blue button">
                    <i class="book icon"></i> Dokumentation
                </a>
                <button onclick="window.location.reload();" class="ui green button">
                    <i class="sync icon"></i> Erneut prüfen
                </button>
            </div>
        </div>
    </div>
    
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script src="assets/js/i18n.js"></script>
    <script>
        // Initialize components
        $(document).ready(function() {
            // Initialize dropdown
            $('.ui.dropdown').dropdown();
            
            // Initialize i18n
            if (typeof FormWerkI18n !== 'undefined') {
                window.i18n = new FormWerkI18n();
                window.i18n.init();
            }
            
            // Smooth scroll to errors
            if (<?php echo $errors; ?> > 0) {
                setTimeout(function() {
                    $('html, body').animate({
                        scrollTop: $('.check-item.error').first().offset().top - 100
                    }, 1000);
                }, 500);
            }
        });
    </script>
</body>
</html>