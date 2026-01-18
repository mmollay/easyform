<?php
/**
 * EasyList - Advanced Demo mit Smartform2 Features
 * Zeigt alle neuen Features: Database-Integration, Button-System, Modals, Conditional Rendering
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyList;
use EasyForm\EasyForm;

// Mock Database Connection
class MockDatabase {
    public function query($sql) {
        // Simulate database query result
        $result = new class {
            private $data;
            private $position = 0;

            public function __construct() {
                $this->data = [
                    ['id' => 1, 'title' => 'Newsletter Januar 2024', 'status' => 'draft', 'recipients' => 150, 'send_status' => 0, 'created' => '2024-01-15 10:30:00', 'scheduled' => null],
                    ['id' => 2, 'title' => 'Sommeraktion 2024', 'status' => 'scheduled', 'recipients' => 320, 'send_status' => 0, 'created' => '2024-01-20 14:15:00', 'scheduled' => '2024-06-01 09:00:00'],
                    ['id' => 3, 'title' => 'Willkommensmail Neue Mitglieder', 'status' => 'sent', 'recipients' => 45, 'send_status' => 1, 'created' => '2024-01-10 08:00:00', 'scheduled' => null],
                    ['id' => 4, 'title' => 'Wöchentlicher Newsletter #45', 'status' => 'sent', 'recipients' => 280, 'send_status' => 1, 'created' => '2024-01-08 16:45:00', 'scheduled' => null],
                    ['id' => 5, 'title' => 'Event-Ankündigung: Frühjahrskonferenz', 'status' => 'draft', 'recipients' => 95, 'send_status' => 0, 'created' => '2024-01-22 11:20:00', 'scheduled' => null],
                    ['id' => 6, 'title' => 'Black Friday Deals', 'status' => 'draft', 'recipients' => 450, 'send_status' => 0, 'created' => '2024-01-18 13:30:00', 'scheduled' => null],
                ];
            }

            public function fetch_assoc() {
                if ($this->position >= count($this->data)) {
                    return null;
                }
                return $this->data[$this->position++];
            }
        };
        return $result;
    }

    public function real_escape_string($str) {
        return addslashes($str);
    }
}

$db = new MockDatabase();

// Newsletter-Liste mit allen neuen Features
$newsletterList = new EasyList('newsletter_list');
$newsletterList
    ->fromDatabase("
        SELECT
            id,
            title,
            status,
            recipients,
            send_status,
            created,
            scheduled
        FROM newsletters
        ORDER BY created DESC
    ", $db, true)

    // Searchable columns definieren
    ->setSearchableColumns(['title', 'status'])

    // Filter hinzufügen (Smartform2 kompatibel)
    ->addFilter('status', 'Status', [
        '' => 'Alle',
        'draft' => 'Entwurf',
        'scheduled' => 'Geplant',
        'sent' => 'Gesendet'
    ])

    // Spalten mit Formattern und allowHtml
    ->column('title', 'Newsletter Titel', [
        'searchable' => true,
        'formatter' => function($value, $row) {
            $icon = $row['status'] === 'sent' ? 'check circle' :
                   ($row['status'] === 'scheduled' ? 'clock' : 'file outline');
            return '<i class="' . $icon . ' icon"></i> ' . htmlspecialchars($value);
        },
        'allowHtml' => true
    ])

    ->column('status', 'Status', [
        'width' => '150px',
        'formatter' => function($value, $row) {
            $colors = [
                'draft' => 'grey',
                'scheduled' => 'blue',
                'sent' => 'green'
            ];
            $labels = [
                'draft' => 'Entwurf',
                'scheduled' => 'Geplant',
                'sent' => 'Gesendet'
            ];
            $color = $colors[$value] ?? 'grey';
            $label = $labels[$value] ?? $value;
            return '<span class="ui ' . $color . ' label">' . $label . '</span>';
        },
        'allowHtml' => true
    ])

    ->column('recipients', 'Empfänger', [
        'width' => '120px',
        'align' => 'center',
        'formatter' => function($value, $row) {
            $color = $value > 200 ? 'green' : ($value > 100 ? 'blue' : 'grey');
            return '<span class="ui ' . $color . ' circular label">' . $value . '</span>';
        },
        'allowHtml' => true
    ])

    ->column('created', 'Erstellt', [
        'width' => '180px',
        'formatter' => function($value, $row) {
            $date = new DateTime($value);
            return $date->format('d.m.Y H:i');
        }
    ])

    ->column('scheduled', 'Geplant für', [
        'width' => '180px',
        'formatter' => function($value, $row) {
            if (!$value) {
                return '<span class="ui grey text">-</span>';
            }
            $date = new DateTime($value);
            return '<i class="clock icon"></i> ' . $date->format('d.m.Y H:i');
        },
        'allowHtml' => true
    ])

    // Button Column Titles setzen
    ->setButtonColumnTitle('left', 'Aktionen', 'center')
    ->setButtonColumnTitle('right', 'Versand', 'center')

    // Left Buttons mit Conditional Rendering
    ->addButton('edit', [
        'icon' => 'edit',
        'class' => 'ui mini blue button',
        'position' => 'left',
        'modalId' => 'editModal',
        'params' => ['update_id' => 'id'],
        'popup' => 'Newsletter bearbeiten',
        'conditions' => [
            function($row) {
                // Nur editierbar wenn noch nicht gesendet
                return $row['send_status'] == 0;
            }
        ]
    ])

    ->addButton('preview', [
        'icon' => 'eye',
        'class' => 'ui mini button',
        'position' => 'left',
        'modalId' => 'previewModal',
        'params' => ['id' => 'id'],
        'popup' => 'Vorschau anzeigen'
    ])

    ->addButton('log', [
        'icon' => 'list',
        'class' => 'ui mini button',
        'position' => 'left',
        'modalId' => 'logModal',
        'params' => ['newsletter_id' => 'id'],
        'popup' => 'Versandprotokoll',
        'conditions' => [
            function($row) {
                // Nur wenn Newsletter bereits gesendet wurde
                return $row['send_status'] == 1;
            }
        ]
    ])

    ->addButton('delete', [
        'icon' => 'trash',
        'class' => 'ui mini red button',
        'position' => 'left',
        'callback' => 'deleteNewsletter',
        'params' => ['id' => 'id', 'title' => 'title'],
        'popup' => 'Newsletter löschen',
        'conditions' => [
            function($row) {
                // Nur löschbar wenn noch nicht gesendet
                return $row['send_status'] == 0;
            }
        ]
    ])

    // Right Buttons für Versand-Aktionen
    ->addButton('send_test', [
        'icon' => 'paper plane',
        'class' => 'ui mini teal button',
        'position' => 'right',
        'callback' => 'sendTestEmail',
        'params' => ['id' => 'id'],
        'popup' => 'Test-Mail senden',
        'conditions' => [
            function($row) {
                return $row['send_status'] == 0;
            }
        ]
    ])

    ->addButton('schedule', [
        'icon' => 'clock',
        'class' => 'ui mini blue button',
        'position' => 'right',
        'modalId' => 'scheduleModal',
        'params' => ['id' => 'id'],
        'popup' => 'Versand planen',
        'conditions' => [
            function($row) {
                return $row['send_status'] == 0 && $row['status'] !== 'scheduled';
            }
        ]
    ])

    ->addButton('send_now', [
        'icon' => 'send',
        'class' => 'ui mini green button',
        'position' => 'right',
        'callback' => 'sendNewsletter',
        'params' => ['id' => 'id', 'title' => 'title'],
        'popup' => 'Jetzt senden',
        'conditions' => [
            function($row) {
                return $row['send_status'] == 0;
            }
        ]
    ])

    // External Buttons (Toolbar)
    ->addExternalButton('new', [
        'icon' => 'plus',
        'title' => 'Neu',
        'class' => 'ui primary button',
        'position' => 'inline',
        'alignment' => 'right',
        'modalId' => 'editModal',
        'popup' => 'Neuen Newsletter erstellen'
    ])

    ->addExternalButton('export', [
        'icon' => 'download',
        'title' => 'Export',
        'class' => 'ui button',
        'position' => 'inline',
        'alignment' => 'right',
        'callback' => 'exportNewsletters',
        'popup' => 'Alle Newsletter exportieren'
    ])

    // Modals definieren
    ->addModal('editModal', [
        'title' => 'Newsletter bearbeiten',
        'content' => 'forms/newsletter_form.php',
        'size' => 'large',
        'method' => 'POST'
    ])

    ->addModal('previewModal', [
        'title' => 'Newsletter Vorschau',
        'content' => 'preview/newsletter_preview.php',
        'size' => 'large',
        'scrolling' => true
    ])

    ->addModal('logModal', [
        'title' => 'Versandprotokoll',
        'content' => 'logs/newsletter_log.php',
        'size' => 'small',
        'scrolling' => true
    ])

    ->addModal('scheduleModal', [
        'title' => 'Versand planen',
        'content' => 'forms/schedule_form.php',
        'size' => 'small'
    ])

    ->searchable(true, 'Newsletter durchsuchen...')
    ->sortable(true)
    ->paginate(true, 10);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyList Advanced Demo - Smartform2 Features</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .demo-header {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .demo-header h1 {
            font-size: 2.5em;
            color: #2d3748;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .demo-header h1 i {
            color: #667eea;
        }

        .demo-subtitle {
            color: #718096;
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .feature-card {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .feature-card h3 {
            color: #2d3748;
            font-size: 1em;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feature-card p {
            color: #718096;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .feature-card i {
            color: #667eea;
        }

        .demo-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5em;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Button Cell Styling */
        .button-cell {
            white-space: nowrap;
            text-align: center !important;
            background: #f8f9fa !important;
        }

        .button-cell .ui.button {
            margin: 2px;
        }

        /* Back Navigation */
        .back-nav {
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.95);
            color: #2d3748;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Code Example Styling */
        .code-example {
            background: #1a202c;
            color: #e2e8f0;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            overflow-x: auto;
        }

        .code-example h3 {
            color: #e2e8f0;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .code-example pre {
            margin: 0;
            font-family: 'Fira Code', 'Monaco', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .demo-header {
                padding: 25px;
            }

            .demo-header h1 {
                font-size: 1.8em;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Back Navigation -->
        <div class="back-nav">
            <a href="index.php" class="back-btn">
                <i class="arrow left icon"></i>
                Zurück zu Beispielen
            </a>
        </div>

        <!-- Header -->
        <div class="demo-header">
            <h1>
                <i class="magic icon"></i>
                EasyList Advanced Demo
            </h1>
            <p class="demo-subtitle">
                Vollständige Smartform2 Kompatibilität mit Database-Integration, Button-System und Modal-Support
            </p>

            <div class="feature-grid">
                <div class="feature-card">
                    <h3><i class="database icon"></i> Database Integration</h3>
                    <p>Direktes Laden aus Datenbank mit fromDatabase() und Prepared Statements</p>
                </div>
                <div class="feature-card">
                    <h3><i class="hand pointer icon"></i> Button System</h3>
                    <p>Buttons mit position (left/right), conditions und modalId/callback</p>
                </div>
                <div class="feature-card">
                    <h3><i class="check circle icon"></i> Conditional Rendering</h3>
                    <p>Buttons per Closure-Conditions dynamisch ein-/ausblenden</p>
                </div>
                <div class="feature-card">
                    <h3><i class="window maximize icon"></i> Modal Integration</h3>
                    <p>Modals mit AJAX-Content-Loading und Parameter-Übergabe</p>
                </div>
                <div class="feature-card">
                    <h3><i class="code icon"></i> Formatter & allowHtml</h3>
                    <p>Closures für Cell-Formatierung mit Zugriff auf Row-Daten</p>
                </div>
                <div class="feature-card">
                    <h3><i class="filter icon"></i> Filter & Search</h3>
                    <p>addFilter() und setSearchableColumns() Methoden</p>
                </div>
            </div>
        </div>

        <!-- Newsletter List -->
        <div class="demo-section">
            <h2 class="section-title">
                <i class="mail icon"></i> Newsletter Verwaltung
            </h2>

            <div class="ui info message">
                <div class="header">Demo-Features</div>
                <ul class="list">
                    <li><strong>Left Button Column:</strong> Edit (nur draft), Preview (alle), Log (nur sent), Delete (nur draft)</li>
                    <li><strong>Right Button Column:</strong> Test-Mail, Planen, Jetzt senden (alle nur für draft)</li>
                    <li><strong>External Buttons:</strong> Neu erstellen, Export (in Toolbar rechts)</li>
                    <li><strong>Conditional Rendering:</strong> Buttons je nach Status und send_status</li>
                    <li><strong>Modal Trigger:</strong> Edit, Preview, Log, Schedule öffnen Modals</li>
                    <li><strong>Callback Trigger:</strong> Delete, Send Test, Send Now führen JavaScript aus</li>
                </ul>
            </div>

            <?php echo $newsletterList->generateList(); ?>
        </div>

        <!-- Code Examples -->
        <div class="demo-section">
            <h2 class="section-title">
                <i class="code icon"></i> Code-Beispiele
            </h2>

            <div class="code-example">
                <h3><i class="database icon"></i> 1. Database Integration</h3>
                <pre><?php echo htmlspecialchars('<?php
// Mit MySQLi Connection und Prepared Statements
$list = new EasyList(\'users\');
$list->fromDatabase("
    SELECT id, name, email, status
    FROM users
    WHERE active = ?
    ORDER BY created DESC
", $db, true, "i", [1])

// Oder mit setDatabase (Smartform2 kompatibel)
$list->setDatabase($db, "SELECT * FROM users", true);
?>'); ?></pre>
            </div>

            <div class="code-example">
                <h3><i class="hand pointer icon"></i> 2. Button System mit Conditions</h3>
                <pre><?php echo htmlspecialchars('<?php
$list->addButton(\'edit\', [
    \'icon\' => \'edit\',
    \'position\' => \'left\',      // \'left\' oder \'right\'
    \'modalId\' => \'editModal\',  // Öffnet Modal
    \'params\' => [\'id\' => \'id\'], // Row-Daten als Parameter
    \'popup\' => \'Bearbeiten\',
    \'conditions\' => [           // Conditional Rendering
        function($row) {
            return $row[\'status\'] !== \'sent\';
        }
    ]
]);

// Button mit JavaScript Callback statt Modal
$list->addButton(\'delete\', [
    \'icon\' => \'trash\',
    \'callback\' => \'deleteItem\',  // JavaScript-Funktion
    \'params\' => [\'id\' => \'id\', \'name\' => \'title\'],
    \'conditions\' => [
        function($row) { return $row[\'deletable\'] == 1; }
    ]
]);
?>'); ?></pre>
            </div>

            <div class="code-example">
                <h3><i class="window maximize icon"></i> 3. Modal Integration</h3>
                <pre><?php echo htmlspecialchars('<?php
$list->addModal(\'editModal\', [
    \'title\' => \'Bearbeiten\',
    \'content\' => \'forms/edit.php\',  // PHP-Datei via AJAX laden
    \'size\' => \'large\',              // small/large/fullscreen
    \'method\' => \'POST\',             // GET/POST
    \'scrolling\' => true
]);

// External Button im Toolbar
$list->addExternalButton(\'new\', [
    \'icon\' => \'plus\',
    \'title\' => \'Neu\',
    \'modalId\' => \'editModal\',
    \'alignment\' => \'right\'
]);
?>'); ?></pre>
            </div>

            <div class="code-example">
                <h3><i class="code icon"></i> 4. Formatter mit allowHtml</h3>
                <pre><?php echo htmlspecialchars('<?php
$list->column(\'status\', \'Status\', [
    \'formatter\' => function($value, $row) {
        // Zugriff auf gesamte Row-Daten!
        $color = $row[\'is_active\'] ? \'green\' : \'red\';
        return \'<span class="ui \' . $color . \' label">\' . $value . \'</span>\';
    },
    \'allowHtml\' => true  // Wichtig: HTML nicht escapen
]);
?>'); ?></pre>
            </div>

            <div class="code-example">
                <h3><i class="columns icon"></i> 5. Button Column Titles</h3>
                <pre><?php echo htmlspecialchars('<?php
// Titel für Button-Spalten setzen
$list->setButtonColumnTitle(\'left\', \'Aktionen\', \'center\');
$list->setButtonColumnTitle(\'right\', \'Versand\', \'center\');
?>'); ?></pre>
            </div>

            <div class="code-example">
                <h3><i class="filter icon"></i> 6. Filter & Searchable Columns</h3>
                <pre><?php echo htmlspecialchars('<?php
// Filter hinzufügen (Smartform2 kompatibel)
$list->addFilter(\'status\', \'Status\', [
    \'\' => \'Alle\',
    \'active\' => \'Aktiv\',
    \'inactive\' => \'Inaktiv\'
], [\'defaultValue\' => \'active\']);

// Durchsuchbare Spalten definieren
$list->setSearchableColumns([\'title\', \'description\', \'email\']);
?>'); ?></pre>
            </div>
        </div>
    </div>

    <!-- Dummy Modal Content Forms (würden normalerweise eigene PHP-Dateien sein) -->
    <div style="display: none;">
        <div id="dummy-form-content">
            <form class="ui form">
                <div class="field">
                    <label>Titel</label>
                    <input type="text" placeholder="Newsletter Titel...">
                </div>
                <div class="field">
                    <label>Empfängergruppe</label>
                    <select class="ui dropdown">
                        <option value="">Gruppe auswählen</option>
                        <option value="1">Alle Mitglieder</option>
                        <option value="2">Premium User</option>
                    </select>
                </div>
                <button type="submit" class="ui primary button">Speichern</button>
            </form>
        </div>
    </div>

    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>

    <script>
        // Callback Functions für Buttons

        function deleteNewsletter(params) {
            if (confirm(`Newsletter "${params.title}" wirklich löschen?`)) {
                console.log('Lösche Newsletter ID:', params.id);
                $('body').toast({
                    class: 'success',
                    message: `Newsletter "${params.title}" gelöscht (Demo)`,
                    showProgress: 'bottom'
                });

                // In echter Anwendung: AJAX-Request zum Löschen
                // $.post('ajax/delete_newsletter.php', params, function(response) { ... });
            }
        }

        function sendTestEmail(params) {
            console.log('Sende Test-Mail für Newsletter ID:', params.id);
            $('body').toast({
                class: 'info',
                message: 'Test-Mail wird gesendet... (Demo)',
                showProgress: 'bottom'
            });

            // In echter Anwendung: AJAX-Request
            // $.post('ajax/send_test.php', params, function(response) { ... });
        }

        function sendNewsletter(params) {
            if (confirm(`Newsletter "${params.title}" jetzt an alle Empfänger senden?`)) {
                console.log('Sende Newsletter ID:', params.id);
                $('body').toast({
                    class: 'success',
                    message: `Newsletter "${params.title}" wird gesendet... (Demo)`,
                    showProgress: 'bottom',
                    displayTime: 5000
                });

                // In echter Anwendung: AJAX-Request zum Versenden
                // $.post('ajax/send_newsletter.php', params, function(response) { ... });
            }
        }

        function exportNewsletters() {
            console.log('Exportiere alle Newsletter...');
            $('body').toast({
                class: 'info',
                message: 'Newsletter werden exportiert... (Demo)',
                showProgress: 'bottom'
            });

            // In echter Anwendung: Download-Link generieren
            // window.location.href = 'ajax/export_newsletters.php';
        }

        // Reload-Funktion für Modal-Forms
        function reloadTable() {
            console.log('Liste würde neu geladen werden...');
            $('body').toast({
                class: 'success',
                message: 'Änderungen gespeichert! (Demo - kein Reload)',
                showProgress: 'bottom'
            });
        }

        // Initialize Semantic UI
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
        });
    </script>
</body>
</html>
