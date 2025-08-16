<?php
/**
 * EasyList Advanced Examples
 * Demonstriert erweiterte Features und Anwendungsfälle
 */

session_start();
require_once '../autoload.php';
use EasyForm\EasyList;

// Beispiel 1: Mitarbeiter-Dashboard mit komplexen Filtern
$employees = [
    ['id' => 1, 'emp_id' => 'EMP001', 'name' => 'Maria Müller', 'avatar' => 'https://i.pravatar.cc/150?img=1', 
     'department' => 'IT', 'position' => 'Senior Developer', 'salary' => 75000, 'start_date' => '2020-03-15', 
     'performance' => 95, 'status' => 'Aktiv', 'skills' => 'PHP, JavaScript, MySQL'],
    ['id' => 2, 'emp_id' => 'EMP002', 'name' => 'Klaus Fischer', 'avatar' => 'https://i.pravatar.cc/150?img=2',
     'department' => 'Vertrieb', 'position' => 'Sales Manager', 'salary' => 65000, 'start_date' => '2019-07-01', 
     'performance' => 88, 'status' => 'Aktiv', 'skills' => 'Kommunikation, CRM, Excel'],
    ['id' => 3, 'emp_id' => 'EMP003', 'name' => 'Lisa Wagner', 'avatar' => 'https://i.pravatar.cc/150?img=3',
     'department' => 'Marketing', 'position' => 'Marketing Lead', 'salary' => 60000, 'start_date' => '2021-01-10', 
     'performance' => 92, 'status' => 'Aktiv', 'skills' => 'SEO, Content, Social Media'],
    ['id' => 4, 'emp_id' => 'EMP004', 'name' => 'Thomas Bauer', 'avatar' => 'https://i.pravatar.cc/150?img=4',
     'department' => 'IT', 'position' => 'Junior Developer', 'salary' => 45000, 'start_date' => '2022-06-01', 
     'performance' => 78, 'status' => 'Aktiv', 'skills' => 'HTML, CSS, JavaScript'],
    ['id' => 5, 'emp_id' => 'EMP005', 'name' => 'Sandra Klein', 'avatar' => 'https://i.pravatar.cc/150?img=5',
     'department' => 'HR', 'position' => 'HR Manager', 'salary' => 55000, 'start_date' => '2020-09-15', 
     'performance' => 85, 'status' => 'Urlaub', 'skills' => 'Recruiting, Arbeitsrecht, SAP'],
];

$employeeList = new EasyList('employee_dashboard');
$employeeList->data($employees)
    ->column('emp_id', 'Mitarbeiter', [
        'width' => '200px',
        'format' => function($value, $row) {
            return '
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img class="ui mini circular image" src="' . $row['avatar'] . '" alt="">
                    <div>
                        <strong>' . $row['name'] . '</strong><br>
                        <small style="color: #888;">' . $value . '</small>
                    </div>
                </div>
            ';
        }
    ])
    ->column('department', 'Abteilung', [
        'filter' => [
            'type' => 'select',
            'options' => ['IT', 'Vertrieb', 'Marketing', 'HR', 'Finance']
        ],
        'format' => function($value) {
            $colors = [
                'IT' => 'blue',
                'Vertrieb' => 'green',
                'Marketing' => 'purple',
                'HR' => 'orange',
                'Finance' => 'teal'
            ];
            $color = $colors[$value] ?? 'grey';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    ->column('position', 'Position')
    ->column('salary', 'Gehalt', [
        'type' => 'number',
        'align' => 'right',
        'format' => function($value) {
            return '€ ' . number_format($value, 0, ',', '.') . ' / Jahr';
        },
        'filter' => [
            'type' => 'range',
            'min' => 40000,
            'max' => 100000
        ]
    ])
    ->column('performance', 'Leistung', [
        'type' => 'number',
        'align' => 'center',
        'format' => function($value) {
            $color = $value >= 90 ? 'green' : ($value >= 70 ? 'yellow' : 'red');
            return '
                <div class="ui small ' . $color . ' progress" data-percent="' . $value . '">
                    <div class="bar" style="width: ' . $value . '%;"></div>
                    <div class="label">' . $value . '%</div>
                </div>
            ';
        }
    ])
    ->column('start_date', 'Eintrittsdatum', [
        'type' => 'date',
        'format' => function($value) {
            $date = new DateTime($value);
            $now = new DateTime();
            $interval = $date->diff($now);
            $years = $interval->y;
            $months = $interval->m;
            
            $duration = $years > 0 ? $years . ' Jahr' . ($years > 1 ? 'e' : '') : '';
            if ($months > 0) {
                $duration .= ($duration ? ', ' : '') . $months . ' Monat' . ($months > 1 ? 'e' : '');
            }
            
            return date('d.m.Y', strtotime($value)) . '<br><small style="color: #888;">(' . $duration . ')</small>';
        }
    ])
    ->column('status', 'Status', [
        'filter' => [
            'type' => 'select',
            'options' => ['Aktiv', 'Urlaub', 'Krank', 'Homeoffice']
        ],
        'format' => function($value) {
            $icons = [
                'Aktiv' => 'check circle',
                'Urlaub' => 'plane',
                'Krank' => 'medkit',
                'Homeoffice' => 'home'
            ];
            $colors = [
                'Aktiv' => 'green',
                'Urlaub' => 'blue',
                'Krank' => 'red',
                'Homeoffice' => 'teal'
            ];
            $icon = $icons[$value] ?? 'question';
            $color = $colors[$value] ?? 'grey';
            return '<span class="ui ' . $color . ' label"><i class="' . $icon . ' icon"></i>' . $value . '</span>';
        }
    ])
    ->searchable(true, 'Mitarbeiter suchen...')
    ->sortable(true)
    ->paginate(true, 10)
    ->exportable(true, ['csv', 'excel'])
    ->selectable(true)
    ->bulkActions([
        'message' => ['label' => 'Nachricht senden', 'icon' => 'mail'],
        'export' => ['label' => 'Exportieren', 'icon' => 'download'],
        'archive' => ['label' => 'Archivieren', 'icon' => 'archive']
    ])
    ->style('ui very basic table', [
        'striped' => false,
        'compact' => false,
        'hover' => true
    ]);

// Beispiel 2: Aufgaben-Management mit Prioritäten
$tasks = [
    ['id' => 1, 'title' => 'Website Redesign', 'project' => 'Marketing 2024', 'assignee' => 'Team A',
     'priority' => 'Hoch', 'status' => 'In Bearbeitung', 'progress' => 65, 'due_date' => '2024-12-15',
     'tags' => 'design,frontend,urgent'],
    ['id' => 2, 'title' => 'API Dokumentation', 'project' => 'Development', 'assignee' => 'Max M.',
     'priority' => 'Mittel', 'status' => 'Offen', 'progress' => 20, 'due_date' => '2024-12-20',
     'tags' => 'documentation,api'],
    ['id' => 3, 'title' => 'Kundenmeeting vorbereiten', 'project' => 'Sales Q4', 'assignee' => 'Sarah K.',
     'priority' => 'Hoch', 'status' => 'Abgeschlossen', 'progress' => 100, 'due_date' => '2024-11-30',
     'tags' => 'meeting,client'],
    ['id' => 4, 'title' => 'Security Audit', 'project' => 'IT Infrastructure', 'assignee' => 'Security Team',
     'priority' => 'Kritisch', 'status' => 'In Bearbeitung', 'progress' => 40, 'due_date' => '2024-12-10',
     'tags' => 'security,audit,compliance'],
    ['id' => 5, 'title' => 'Newsletter versenden', 'project' => 'Marketing 2024', 'assignee' => 'Lisa W.',
     'priority' => 'Niedrig', 'status' => 'Geplant', 'progress' => 0, 'due_date' => '2024-12-25',
     'tags' => 'newsletter,marketing'],
];

$taskList = new EasyList('task_management');
$taskList->data($tasks)
    ->column('priority', '', [
        'width' => '50px',
        'align' => 'center',
        'sortable' => true,
        'format' => function($value) {
            $colors = [
                'Kritisch' => 'red',
                'Hoch' => 'orange',
                'Mittel' => 'yellow',
                'Niedrig' => 'green'
            ];
            $color = $colors[$value] ?? 'grey';
            return '<i class="large ' . $color . ' circle icon" title="' . $value . '"></i>';
        }
    ])
    ->column('title', 'Aufgabe', [
        'format' => function($value, $row) {
            $tags = explode(',', $row['tags']);
            $tagHtml = '';
            foreach ($tags as $tag) {
                $tagHtml .= '<span class="ui tiny basic label">' . trim($tag) . '</span> ';
            }
            return '<strong>' . $value . '</strong><br>' . $tagHtml;
        }
    ])
    ->column('project', 'Projekt', [
        'filter' => [
            'type' => 'select',
            'options' => ['Marketing 2024', 'Development', 'Sales Q4', 'IT Infrastructure']
        ]
    ])
    ->column('assignee', 'Zugewiesen', [
        'format' => function($value) {
            $initials = '';
            $parts = explode(' ', $value);
            foreach ($parts as $part) {
                $initials .= substr($part, 0, 1);
            }
            return '
                <div class="ui image label">
                    <div class="ui mini circular label" style="background: #667eea; color: white;">' . $initials . '</div>
                    ' . $value . '
                </div>
            ';
        }
    ])
    ->column('progress', 'Fortschritt', [
        'type' => 'number',
        'align' => 'center',
        'width' => '150px',
        'format' => function($value) {
            $color = $value === 100 ? 'green' : ($value >= 50 ? 'blue' : 'grey');
            return '
                <div class="ui small ' . $color . ' progress">
                    <div class="bar" style="width: ' . $value . '%;"></div>
                </div>
                <small>' . $value . '%</small>
            ';
        }
    ])
    ->column('status', 'Status', [
        'filter' => [
            'type' => 'select',
            'options' => ['Offen', 'In Bearbeitung', 'Review', 'Abgeschlossen', 'Geplant']
        ],
        'format' => function($value) {
            $colors = [
                'Offen' => 'grey',
                'In Bearbeitung' => 'blue',
                'Review' => 'purple',
                'Abgeschlossen' => 'green',
                'Geplant' => 'teal'
            ];
            $color = $colors[$value] ?? 'grey';
            return '<span class="ui ' . $color . ' label">' . $value . '</span>';
        }
    ])
    ->column('due_date', 'Fällig', [
        'type' => 'date',
        'format' => function($value) {
            $date = new DateTime($value);
            $now = new DateTime();
            $diff = $now->diff($date);
            
            if ($date < $now) {
                $color = 'red';
                $text = 'Überfällig (' . $diff->days . ' Tage)';
            } elseif ($diff->days <= 3) {
                $color = 'orange';
                $text = 'In ' . $diff->days . ' Tagen';
            } else {
                $color = 'green';
                $text = date('d.m.Y', strtotime($value));
            }
            
            return '<span style="color: ' . $color . ';">' . $text . '</span>';
        }
    ])
    ->searchable(true, 'Aufgaben durchsuchen...')
    ->sortable(true)
    ->paginate(true, 10)
    ->actions([
        ['label' => 'Bearbeiten', 'icon' => 'edit', 'class' => 'mini'],
        ['label' => 'Details', 'icon' => 'eye', 'class' => 'mini'],
        ['label' => 'Löschen', 'icon' => 'trash', 'class' => 'mini red']
    ]);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyList - Erweiterte Beispiele</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #f6f8fb 0%, #e9ecf0 100%);
            padding: 40px 20px;
        }
        
        .example-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .example-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .example-title {
            font-size: 1.8rem;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .example-description {
            color: #718096;
            font-size: 1.1rem;
        }
        
        .feature-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .feature-tag {
            padding: 5px 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .back-nav {
            margin-bottom: 30px;
        }
        
        /* Custom progress bar styles */
        .ui.progress {
            margin: 0;
        }
        
        .ui.progress .bar {
            min-width: 0;
            border-radius: 10px;
        }
        
        .ui.progress .label {
            font-size: 0.85em;
            font-weight: 600;
        }
        
        /* Better table styling */
        .ui.very.basic.table {
            border: none;
        }
        
        .ui.very.basic.table thead th {
            background: #f8f9fa;
            font-weight: 600;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="ui container">
        <!-- Navigation -->
        <div class="back-nav">
            <a href="index.php" class="ui button">
                <i class="arrow left icon"></i> Zurück zu Beispielen
            </a>
            <a href="../docs_list.php" class="ui primary button">
                <i class="book icon"></i> Dokumentation
            </a>
            <a href="../list_generator.php" class="ui purple button">
                <i class="magic icon"></i> List Generator
            </a>
        </div>
        
        <!-- Example 1: Employee Dashboard -->
        <div class="example-section">
            <div class="example-header">
                <h2 class="example-title">
                    <i class="users icon"></i> Mitarbeiter-Dashboard
                </h2>
                <p class="example-description">
                    Komplexes Dashboard mit Avatar-Integration, Performance-Metriken und erweiterten Formatierungen
                </p>
                <div class="feature-tags">
                    <span class="feature-tag">Avatar-Integration</span>
                    <span class="feature-tag">Progress Bars</span>
                    <span class="feature-tag">Bereichsfilter</span>
                    <span class="feature-tag">Status-Icons</span>
                    <span class="feature-tag">Zeitberechnung</span>
                </div>
            </div>
            
            <?php $employeeList->display(); ?>
        </div>
        
        <!-- Example 2: Task Management -->
        <div class="example-section">
            <div class="example-header">
                <h2 class="example-title">
                    <i class="tasks icon"></i> Aufgaben-Management
                </h2>
                <p class="example-description">
                    Projektmanagement-System mit Prioritäten, Fortschrittsanzeige und Fälligkeits-Tracking
                </p>
                <div class="feature-tags">
                    <span class="feature-tag">Prioritäts-Indikatoren</span>
                    <span class="feature-tag">Tags-System</span>
                    <span class="feature-tag">Fortschrittsbalken</span>
                    <span class="feature-tag">Fälligkeits-Warnung</span>
                    <span class="feature-tag">Benutzer-Avatare</span>
                </div>
            </div>
            
            <?php $taskList->display(); ?>
        </div>
        
        <!-- Code Example -->
        <div class="example-section">
            <div class="example-header">
                <h2 class="example-title">
                    <i class="code icon"></i> Code-Beispiel: Custom Formatierung
                </h2>
                <p class="example-description">
                    So erstellen Sie erweiterte Formatierungen mit Callbacks
                </p>
            </div>
            
            <pre class="ui segment" style="background: #1a202c; color: #e2e8f0; padding: 20px; border-radius: 8px;">
<code><?php echo htmlspecialchars("// Avatar mit Mitarbeiter-Info
->column('emp_id', 'Mitarbeiter', [
    'format' => function(\$value, \$row) {
        return '
            <div style=\"display: flex; align-items: center; gap: 10px;\">
                <img class=\"ui mini circular image\" src=\"' . \$row['avatar'] . '\">
                <div>
                    <strong>' . \$row['name'] . '</strong><br>
                    <small>' . \$value . '</small>
                </div>
            </div>
        ';
    }
])

// Fortschrittsbalken
->column('progress', 'Fortschritt', [
    'format' => function(\$value) {
        \$color = \$value >= 90 ? 'green' : (\$value >= 70 ? 'yellow' : 'red');
        return '
            <div class=\"ui small ' . \$color . ' progress\">
                <div class=\"bar\" style=\"width: ' . \$value . '%;\"></div>
                <div class=\"label\">' . \$value . '%</div>
            </div>
        ';
    }
])

// Zeitberechnung
->column('start_date', 'Betriebszugehörigkeit', [
    'format' => function(\$value) {
        \$interval = date_diff(new DateTime(\$value), new DateTime());
        return \$interval->y . ' Jahre, ' . \$interval->m . ' Monate';
    }
])"); ?></code>
            </pre>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <script src="../js/easylist.js"></script>
    
    <script>
        // Initialize progress bars
        $('.ui.progress').progress();
        
        // Custom bulk action handler
        document.addEventListener('easylist:bulkaction', function(e) {
            const { action, ids } = e.detail;
            
            switch(action) {
                case 'message':
                    alert('Nachricht an ' + ids.length + ' Mitarbeiter senden');
                    break;
                case 'archive':
                    if (confirm('Ausgewählte Einträge archivieren?')) {
                        console.log('Archiviere:', ids);
                    }
                    break;
            }
        });
    </script>
</body>
</html>