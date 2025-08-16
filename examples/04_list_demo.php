<?php
/**
 * EasyList - Demo der neuen List-Funktionalität
 * Zeigt verschiedene List-Features wie Filter, Suche, Sortierung und Export
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyList;

// Beispiel-Daten für die Demo
$userData = [
    ['id' => 1, 'name' => 'Max Mustermann', 'email' => 'max@example.com', 'role' => 'Admin', 'status' => 'Aktiv', 'created' => '2024-01-15', 'salary' => 75000],
    ['id' => 2, 'name' => 'Anna Schmidt', 'email' => 'anna@example.com', 'role' => 'User', 'status' => 'Aktiv', 'created' => '2024-02-20', 'salary' => 45000],
    ['id' => 3, 'name' => 'Tom Weber', 'email' => 'tom@example.com', 'role' => 'Editor', 'status' => 'Inaktiv', 'created' => '2024-03-10', 'salary' => 55000],
    ['id' => 4, 'name' => 'Lisa Müller', 'email' => 'lisa@example.com', 'role' => 'Admin', 'status' => 'Aktiv', 'created' => '2023-12-05', 'salary' => 80000],
    ['id' => 5, 'name' => 'Klaus Fischer', 'email' => 'klaus@example.com', 'role' => 'User', 'status' => 'Aktiv', 'created' => '2024-04-18', 'salary' => 42000],
    ['id' => 6, 'name' => 'Maria Wagner', 'email' => 'maria@example.com', 'role' => 'Editor', 'status' => 'Inaktiv', 'created' => '2024-01-30', 'salary' => 58000],
    ['id' => 7, 'name' => 'Peter Bauer', 'email' => 'peter@example.com', 'role' => 'User', 'status' => 'Aktiv', 'created' => '2024-03-25', 'salary' => 48000],
    ['id' => 8, 'name' => 'Sarah Klein', 'email' => 'sarah@example.com', 'role' => 'Admin', 'status' => 'Aktiv', 'created' => '2023-11-12', 'salary' => 82000],
];

$productData = [
    ['id' => 1, 'sku' => 'PRD001', 'name' => 'Laptop Pro 15"', 'category' => 'Elektronik', 'price' => 1299.99, 'stock' => 15, 'status' => 'Verfügbar'],
    ['id' => 2, 'sku' => 'PRD002', 'name' => 'Wireless Mouse', 'category' => 'Zubehör', 'price' => 29.99, 'stock' => 142, 'status' => 'Verfügbar'],
    ['id' => 3, 'sku' => 'PRD003', 'name' => 'USB-C Kabel', 'category' => 'Zubehör', 'price' => 9.99, 'stock' => 0, 'status' => 'Ausverkauft'],
    ['id' => 4, 'sku' => 'PRD004', 'name' => 'Monitor 24"', 'category' => 'Elektronik', 'price' => 299.99, 'stock' => 8, 'status' => 'Verfügbar'],
    ['id' => 5, 'sku' => 'PRD005', 'name' => 'Tastatur Mechanical', 'category' => 'Zubehör', 'price' => 89.99, 'stock' => 25, 'status' => 'Verfügbar'],
];

// Benutzerliste
$userList = new EasyList('user_list');
$userList->data($userData)
    ->column('id', 'ID', ['width' => '80px', 'type' => 'number'])
    ->column('name', 'Name', ['sortable' => true, 'searchable' => true])
    ->column('email', 'E-Mail', ['type' => 'email'])
    ->column('role', 'Rolle', [
        'filter' => [
            'type' => 'select',
            'options' => ['Admin', 'User', 'Editor']
        ]
    ])
    ->column('status', 'Status', [
        'filter' => [
            'type' => 'select', 
            'options' => ['Aktiv', 'Inaktiv']
        ],
        'format' => function($value, $row) {
            $color = $value === 'Aktiv' ? 'green' : 'red';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    ->column('salary', 'Gehalt', [
        'type' => 'number',
        'align' => 'right',
        'format' => function($value, $row) {
            return '€ ' . number_format($value, 0, ',', '.');
        },
        'filter' => [
            'type' => 'range',
            'min' => 40000,
            'max' => 100000
        ]
    ])
    ->column('created', 'Erstellt', ['type' => 'date'])
    ->searchable(true, 'Benutzer suchen...')
    ->sortable(true)
    ->paginate(true, 5)
    ->exportable(true, ['csv', 'excel', 'json'])
    ->selectable(true)
    ->actions([
        [
            'label' => 'Bearbeiten',
            'icon' => 'edit',
            'url' => '#',
            'class' => '',
            'data-action' => 'edit-modal',
            'data-id' => '{id}'
        ],
        [
            'label' => 'Details',
            'icon' => 'eye',
            'url' => '#',
            'class' => 'blue',
            'data-action' => 'view-modal',
            'data-id' => '{id}'
        ],
        [
            'label' => 'Löschen',
            'icon' => 'trash',
            'url' => '#',
            'class' => 'red',
            'data-action' => 'delete',
            'data-id' => '{id}'
        ]
    ])
    ->bulkActions([
        'delete' => ['label' => 'Löschen', 'icon' => 'trash'],
        'activate' => ['label' => 'Aktivieren', 'icon' => 'check'],
        'deactivate' => ['label' => 'Deaktivieren', 'icon' => 'ban']
    ]);

// Produktliste
$productList = new EasyList('product_list');
$productList->data($productData)
    ->column('sku', 'SKU', ['width' => '100px'])
    ->column('name', 'Produkt', ['searchable' => true])
    ->column('category', 'Kategorie', [
        'filter' => [
            'type' => 'select',
            'options' => ['Elektronik', 'Zubehör']
        ]
    ])
    ->column('price', 'Preis', [
        'type' => 'number',
        'align' => 'right',
        'format' => function($value, $row) {
            return '€ ' . number_format($value, 2, ',', '.');
        }
    ])
    ->column('stock', 'Lager', [
        'type' => 'number',
        'align' => 'center',
        'format' => function($value, $row) {
            $color = $value > 10 ? 'green' : ($value > 0 ? 'yellow' : 'red');
            return '<span class="ui ' . $color . ' circular label">' . $value . '</span>';
        }
    ])
    ->column('status', 'Status', [
        'format' => function($value, $row) {
            $color = $value === 'Verfügbar' ? 'green' : 'red';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    ->searchable(true)
    ->sortable(true)
    ->paginate(true, 10)
    ->exportable(true);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyList Demo - Listen mit Filter & Export</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(120deg, #f6f8fb 0%, #e9ecf0 100%);
            min-height: 100vh;
            color: #2c3e50;
        }
        
        .main-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px 0;
            margin-bottom: 40px;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .header-content h1 {
            font-size: 2.5em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .header-subtitle {
            color: #718096;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        
        .header-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50px;
            font-size: 0.9em;
            font-weight: 500;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 50px;
        }
        
        .demo-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .demo-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        
        .section-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .section-title {
            font-size: 1.8em;
            color: #2d3748;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-title i {
            color: #667eea;
        }
        
        .section-description {
            color: #718096;
            font-size: 1.05em;
            line-height: 1.6;
        }
        
        .feature-highlights {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .feature-highlight {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f7fafc;
            border-radius: 20px;
            font-size: 0.9em;
            color: #4a5568;
        }
        
        .feature-highlight i {
            color: #667eea;
        }
        
        /* EasyList Styling */
        .easylist-container {
            margin-top: 20px;
        }
        
        .easylist-toolbar {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            border: 1px solid #e2e8f0;
            border-bottom: none;
        }
        
        .easylist-filters {
            background: #f7fafc;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-bottom: none;
        }
        
        .table-responsive {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 8px 8px;
        }
        
        .ui.table {
            border-radius: 0 0 8px 8px;
            margin: 0;
        }
        
        .ui.table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .ui.table th.sortable {
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .ui.table th.sortable:hover {
            background: #e2e8f0;
        }
        
        .ui.pagination.menu {
            margin-top: 20px;
            justify-content: center;
        }
        
        /* Back Navigation */
        .back-nav {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
        
        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.95);
            color: #4a5568;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .feature-highlights {
                justify-content: center;
            }
            
            .back-nav {
                position: relative;
                margin-bottom: 20px;
            }
            
            .demo-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Back Navigation -->
    <div class="back-nav">
        <a href="index.php" class="back-btn">
            <i class="arrow left icon"></i>
            Zurück zu Beispielen
        </a>
    </div>
    
    <!-- Header -->
    <div class="main-header">
        <div class="header-content">
            <h1><i class="list icon"></i> EasyList Demo</h1>
            <p class="header-subtitle">Listen mit Filter, Suche, Sortierung und Export</p>
            <span class="header-badge">Neue Funktionalität</span>
        </div>
    </div>
    
    <div class="main-container">
        <!-- User List Demo -->
        <div class="demo-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="users icon"></i>
                    Benutzerliste
                </h2>
                <p class="section-description">
                    Umfassende Benutzerverwaltung mit erweiterten Filter- und Export-Funktionen. 
                    Diese Liste demonstriert alle verfügbaren Features von EasyList.
                </p>
                <div class="feature-highlights">
                    <div class="feature-highlight">
                        <i class="search icon"></i>
                        Live-Suche
                    </div>
                    <div class="feature-highlight">
                        <i class="filter icon"></i>
                        Erweiterte Filter
                    </div>
                    <div class="feature-highlight">
                        <i class="sort icon"></i>
                        Sortierung
                    </div>
                    <div class="feature-highlight">
                        <i class="download icon"></i>
                        Multi-Export
                    </div>
                    <div class="feature-highlight">
                        <i class="check square icon"></i>
                        Bulk-Aktionen
                    </div>
                </div>
            </div>
            
            <?php $userList->display(); ?>
        </div>
        
        <!-- Product List Demo -->
        <div class="demo-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="box icon"></i>
                    Produktkatalog
                </h2>
                <p class="section-description">
                    Kompakter Produktkatalog mit Status-Indikatoren und kategorie-basierten Filtern. 
                    Ideal für E-Commerce und Inventory Management.
                </p>
                <div class="feature-highlights">
                    <div class="feature-highlight">
                        <i class="tags icon"></i>
                        Kategorie-Filter
                    </div>
                    <div class="feature-highlight">
                        <i class="warehouse icon"></i>
                        Lager-Status
                    </div>
                    <div class="feature-highlight">
                        <i class="euro icon"></i>
                        Preis-Formatierung
                    </div>
                    <div class="feature-highlight">
                        <i class="table icon"></i>
                        Responsive Design
                    </div>
                </div>
            </div>
            
            <?php $productList->display(); ?>
        </div>
        
        <!-- Code Example -->
        <div class="demo-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="code icon"></i>
                    Code-Beispiel
                </h2>
                <p class="section-description">
                    So erstellen Sie eine EasyList mit nur wenigen Zeilen PHP-Code:
                </p>
            </div>
            
            <div class="ui segment" style="background: #1a202c; color: #e2e8f0; padding: 25px; border-radius: 8px; overflow-x: auto;">
                <pre style="margin: 0; font-family: 'Fira Code', monospace; font-size: 0.9rem; line-height: 1.6;"><code class="language-php" style="color: #e2e8f0;"><?php echo htmlspecialchars('<?php
use EasyForm\EasyList;

$list = new EasyList(\'user_list\');
$list->data($userData)
    ->column(\'name\', \'Name\')
    ->column(\'email\', \'E-Mail\', [\'type\' => \'email\'])
    ->column(\'status\', \'Status\', [
        \'filter\' => [
            \'type\' => \'select\',
            \'options\' => [\'Aktiv\', \'Inaktiv\']
        ]
    ])
    ->searchable(true)
    ->sortable(true)
    ->paginate(true, 10)
    ->exportable(true)
    ->display();
?>'); ?></code></pre>
            </div>
        </div>
        
        <!-- Generator Link -->
        <div class="demo-section" style="text-align: center;">
            <div class="section-header">
                <h2 class="section-title" style="justify-content: center;">
                    <i class="magic icon"></i>
                    List Generator
                </h2>
                <p class="section-description">
                    Erstellen Sie Ihre eigenen Listen visuell mit dem neuen List Generator!
                </p>
            </div>
            
            <a href="../list_generator.php" class="ui huge primary button" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-top: 20px;">
                <i class="magic icon"></i>
                List Generator öffnen
            </a>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="ui modal" id="edit-modal">
        <i class="close icon"></i>
        <div class="header">
            <i class="edit icon"></i> Benutzer bearbeiten
        </div>
        <div class="content">
            <form class="ui form" id="edit-form">
                <input type="hidden" id="edit-user-id">
                <div class="field">
                    <label>Name</label>
                    <input type="text" id="edit-name" placeholder="Name eingeben...">
                </div>
                <div class="field">
                    <label>E-Mail</label>
                    <input type="email" id="edit-email" placeholder="E-Mail eingeben...">
                </div>
                <div class="field">
                    <label>Rolle</label>
                    <select class="ui dropdown" id="edit-role">
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                        <option value="Editor">Editor</option>
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select class="ui dropdown" id="edit-status">
                        <option value="Aktiv">Aktiv</option>
                        <option value="Inaktiv">Inaktiv</option>
                    </select>
                </div>
                <div class="field">
                    <label>Gehalt</label>
                    <input type="number" id="edit-salary" placeholder="Gehalt eingeben...">
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui black deny button">
                Abbrechen
            </div>
            <div class="ui positive right labeled icon button" onclick="saveUser()">
                Speichern
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
    
    <!-- View Modal -->
    <div class="ui modal" id="view-modal">
        <i class="close icon"></i>
        <div class="header">
            <i class="eye icon"></i> Benutzer-Details
        </div>
        <div class="content">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <table class="ui definition table">
                        <tbody>
                            <tr>
                                <td class="four wide">ID</td>
                                <td id="view-id">-</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td id="view-name">-</td>
                            </tr>
                            <tr>
                                <td>E-Mail</td>
                                <td id="view-email">-</td>
                            </tr>
                            <tr>
                                <td>Rolle</td>
                                <td id="view-role">-</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td id="view-status">-</td>
                            </tr>
                            <tr>
                                <td>Gehalt</td>
                                <td id="view-salary">-</td>
                            </tr>
                            <tr>
                                <td>Erstellt am</td>
                                <td id="view-created">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="ui positive button">
                Schließen
            </div>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <script src="../js/easylist.js"></script>
    
    <script>
        // Sample user data for demo (same as PHP data)
        const userData = <?php echo json_encode($userData); ?>;
        
        // Action button click handler
        $(document).on('click', '.ui.button[data-action]', function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const userId = $(this).data('id');
            const user = userData.find(u => u.id == userId);
            
            switch(action) {
                case 'edit-modal':
                    // Populate edit form with user data
                    $('#edit-user-id').val(user.id);
                    $('#edit-name').val(user.name);
                    $('#edit-email').val(user.email);
                    $('#edit-role').val(user.role);
                    $('#edit-status').val(user.status);
                    $('#edit-salary').val(user.salary);
                    
                    // Show modal
                    $('#edit-modal').modal('show');
                    break;
                    
                case 'view-modal':
                    // Populate view modal with user data
                    $('#view-id').text(user.id);
                    $('#view-name').text(user.name);
                    $('#view-email').text(user.email);
                    $('#view-role').html(`<span class="ui label">${user.role}</span>`);
                    $('#view-status').html(`<span class="ui ${user.status === 'Aktiv' ? 'green' : 'red'} label">${user.status}</span>`);
                    $('#view-salary').text('€ ' + Number(user.salary).toLocaleString('de-DE'));
                    $('#view-created').text(new Date(user.created).toLocaleDateString('de-DE'));
                    
                    // Show modal
                    $('#view-modal').modal('show');
                    break;
                    
                case 'delete':
                    if (confirm(`Möchten Sie den Benutzer "${user.name}" wirklich löschen?`)) {
                        // Show success message
                        $('body').toast({
                            class: 'success',
                            message: `Benutzer "${user.name}" wurde gelöscht (Demo - keine echte Löschung)`,
                            showProgress: 'bottom'
                        });
                    }
                    break;
            }
        });
        
        // Save user function
        function saveUser() {
            const userId = $('#edit-user-id').val();
            const name = $('#edit-name').val();
            
            // Close modal
            $('#edit-modal').modal('hide');
            
            // Show success message
            $('body').toast({
                class: 'success',
                message: `Benutzer "${name}" wurde gespeichert (Demo - keine echte Speicherung)`,
                showProgress: 'bottom'
            });
        }
        
        // Custom Bulk Action Handler
        document.addEventListener('easylist:bulkaction', function(e) {
            const { action, ids, listId } = e.detail;
            
            switch(action) {
                case 'delete':
                    if (confirm(`Möchten Sie ${ids.length} Einträge wirklich löschen?`)) {
                        alert(`${ids.length} Einträge würden gelöscht werden: ${ids.join(', ')}`);
                    }
                    break;
                    
                case 'activate':
                    alert(`${ids.length} Benutzer würden aktiviert werden`);
                    break;
                    
                case 'deactivate':
                    alert(`${ids.length} Benutzer würden deaktiviert werden`);
                    break;
                    
                default:
                    console.log(`Unbekannte Aktion: ${action}`);
            }
        });
        
        // Initialize Semantic UI dropdowns
        $('.ui.dropdown').dropdown();
    </script>
</body>
</html>