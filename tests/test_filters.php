<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyList;

// Test data with various fields
$employees = [
    ['id' => 1, 'name' => 'Max Mustermann', 'department' => 'IT', 'position' => 'Developer', 'salary' => 75000, 'start_date' => '2020-01-15', 'status' => 'Aktiv'],
    ['id' => 2, 'name' => 'Anna Schmidt', 'department' => 'HR', 'position' => 'Manager', 'salary' => 65000, 'start_date' => '2019-03-20', 'status' => 'Aktiv'],
    ['id' => 3, 'name' => 'Tom Weber', 'department' => 'Marketing', 'position' => 'Designer', 'salary' => 55000, 'start_date' => '2021-06-10', 'status' => 'Inaktiv'],
    ['id' => 4, 'name' => 'Lisa Müller', 'department' => 'IT', 'position' => 'Lead Developer', 'salary' => 95000, 'start_date' => '2018-11-05', 'status' => 'Aktiv'],
    ['id' => 5, 'name' => 'Klaus Fischer', 'department' => 'Sales', 'position' => 'Sales Rep', 'salary' => 45000, 'start_date' => '2022-02-28', 'status' => 'Aktiv'],
    ['id' => 6, 'name' => 'Maria Wagner', 'department' => 'HR', 'position' => 'Recruiter', 'salary' => 52000, 'start_date' => '2020-07-15', 'status' => 'Urlaub'],
    ['id' => 7, 'name' => 'Peter Bauer', 'department' => 'Marketing', 'position' => 'Content Manager', 'salary' => 58000, 'start_date' => '2021-09-01', 'status' => 'Aktiv'],
    ['id' => 8, 'name' => 'Sarah Klein', 'department' => 'IT', 'position' => 'DevOps', 'salary' => 85000, 'start_date' => '2019-12-10', 'status' => 'Aktiv'],
];

$list = new EasyList('filtered_list');
$list->data($employees)
    ->column('id', 'ID', ['width' => '60px'])
    
    // Text filter for name
    ->column('name', 'Name', [
        'filter' => [
            'type' => 'text',
            'placeholder' => 'Name suchen...'
        ]
    ])
    
    // Select/Dropdown filter for department
    ->column('department', 'Abteilung', [
        'filter' => [
            'type' => 'select',
            'options' => [
                '' => 'Alle Abteilungen',
                'IT' => 'IT',
                'HR' => 'HR', 
                'Marketing' => 'Marketing',
                'Sales' => 'Sales'
            ]
        ],
        'format' => function($value) {
            $colors = [
                'IT' => 'blue',
                'HR' => 'orange',
                'Marketing' => 'purple',
                'Sales' => 'green'
            ];
            $color = $colors[$value] ?? 'grey';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    
    ->column('position', 'Position')
    
    // Range filter for salary
    ->column('salary', 'Gehalt', [
        'type' => 'number',
        'align' => 'right',
        'filter' => [
            'type' => 'range',
            'min' => 40000,
            'max' => 100000,
            'step' => 5000
        ],
        'format' => function($value) {
            return '€ ' . number_format($value, 0, ',', '.');
        }
    ])
    
    // Date filter
    ->column('start_date', 'Eintrittsdatum', [
        'type' => 'date',
        'filter' => [
            'type' => 'date',
            'placeholder' => 'Datum wählen'
        ],
        'format' => function($value) {
            return date('d.m.Y', strtotime($value));
        }
    ])
    
    // Select filter for status
    ->column('status', 'Status', [
        'filter' => [
            'type' => 'select',
            'options' => [
                '' => 'Alle Status',
                'Aktiv' => 'Aktiv',
                'Inaktiv' => 'Inaktiv',
                'Urlaub' => 'Urlaub'
            ]
        ],
        'format' => function($value) {
            $colors = [
                'Aktiv' => 'green',
                'Inaktiv' => 'red',
                'Urlaub' => 'yellow'
            ];
            $color = $colors[$value] ?? 'grey';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    
    ->searchable(true, 'Globale Suche...')
    ->sortable(true)
    ->paginate(true, 10)
    ->exportable(true);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>EasyList - Erweiterte Filter Demo</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        body {
            padding: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-box h2 {
            color: #2d3748;
            margin-bottom: 15px;
        }
        .info-box ul {
            color: #4a5568;
            line-height: 1.8;
        }
        .filter-info {
            background: #e6f7ff;
            border: 1px solid #91d5ff;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .filter-info h4 {
            color: #1890ff;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-box">
            <h1><i class="filter icon"></i> EasyList - Erweiterte Filter Demo</h1>
            <p>Diese Demo zeigt alle verfügbaren Filter-Typen:</p>
            
            <div class="ui grid">
                <div class="eight wide column">
                    <h4><i class="list icon"></i> Verfügbare Filter:</h4>
                    <ul>
                        <li><strong>Text-Filter:</strong> Name-Spalte - Tippe um zu filtern</li>
                        <li><strong>Select-Filter:</strong> Abteilung & Status - Wähle aus Dropdown</li>
                        <li><strong>Range-Filter:</strong> Gehalt - Min/Max Eingabefelder</li>
                        <li><strong>Date-Filter:</strong> Eintrittsdatum - Datum auswählen</li>
                        <li><strong>Globale Suche:</strong> Durchsucht alle Spalten</li>
                    </ul>
                </div>
                <div class="eight wide column">
                    <h4><i class="info circle icon"></i> Hinweise:</h4>
                    <ul>
                        <li>Filter werden automatisch beim Tippen angewendet</li>
                        <li>Mehrere Filter können kombiniert werden</li>
                        <li>Klicke auf Spaltenköpfe zum Sortieren</li>
                        <li>Export berücksichtigt aktive Filter</li>
                        <li>Filter werden in der URL gespeichert (coming soon)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="ui segment">
            <?php $list->display(); ?>
        </div>
        
        <div class="filter-info">
            <h4><i class="code icon"></i> Code-Beispiel für Filter:</h4>
            <pre style="background: #f0f0f0; padding: 10px; border-radius: 4px;">
->column('department', 'Abteilung', [
    'filter' => [
        'type' => 'select',
        'options' => ['IT', 'HR', 'Marketing', 'Sales']
    ]
])

->column('salary', 'Gehalt', [
    'type' => 'number',
    'filter' => [
        'type' => 'range',
        'min' => 40000,
        'max' => 100000
    ]
])</pre>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="examples/index.php" class="ui button">
                <i class="arrow left icon"></i> Zurück zu Beispielen
            </a>
            <a href="docs_list.php" class="ui primary button">
                <i class="book icon"></i> Dokumentation
            </a>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <script src="js/easylist.js"></script>
</body>
</html>