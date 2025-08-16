<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyList;

// Test data
$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'department' => 'IT', 'salary' => 50000],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'department' => 'HR', 'salary' => 55000],
    ['id' => 3, 'name' => 'Bob Wilson', 'email' => 'bob@example.com', 'department' => 'IT', 'salary' => 60000],
    ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'department' => 'Marketing', 'salary' => 52000],
    ['id' => 5, 'name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'department' => 'IT', 'salary' => 58000],
    ['id' => 6, 'name' => 'Eva Martinez', 'email' => 'eva@example.com', 'department' => 'HR', 'salary' => 54000],
    ['id' => 7, 'name' => 'Frank Johnson', 'email' => 'frank@example.com', 'department' => 'Marketing', 'salary' => 56000],
    ['id' => 8, 'name' => 'Grace Lee', 'email' => 'grace@example.com', 'department' => 'IT', 'salary' => 62000],
];

$list = new EasyList('test_features');
$list->data($users)
    ->column('id', 'ID', ['type' => 'number', 'width' => '60px'])
    ->column('name', 'Name')
    ->column('email', 'Email')
    ->column('department', 'Department', [
        'filter' => [
            'type' => 'select',
            'options' => ['IT', 'HR', 'Marketing']
        ]
    ])
    ->column('salary', 'Salary', [
        'type' => 'number',
        'align' => 'right',
        'filter' => [
            'type' => 'range',
            'min' => 50000,
            'max' => 70000
        ]
    ])
    ->searchable(true, 'Search...')
    ->sortable(true)
    ->paginate(true, 5)
    ->exportable(true);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Test EasyList Features</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        body {
            padding: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .debug-info {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        pre {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>EasyList Features Test</h1>
        
        <div class="ui segment">
            <?php $list->display(); ?>
        </div>
        
        <div class="debug-info">
            <h3>Debug Info</h3>
            <p>Check the browser console for JavaScript errors.</p>
            <p>Features that should work:</p>
            <ul>
                <li>Search box at the top</li>
                <li>Click column headers to sort</li>
                <li>Department dropdown filter</li>
                <li>Salary range filter</li>
                <li>Pagination (5 items per page)</li>
                <li>Export buttons</li>
            </ul>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    <script src="./js/easylist.js"></script>
    
    <script>
        // Debug: Check if EasyListHandler is loaded
        console.log('EasyListHandler available:', typeof EasyListHandler !== 'undefined');
        
        // Debug: Log when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            
            // Check if there are any EasyListHandler instances
            if (window.EasyListConfigs) {
                console.log('EasyListConfigs:', window.EasyListConfigs);
            }
        });
        
        // Debug: Listen for custom events
        document.addEventListener('easylist:bulkaction', function(e) {
            console.log('Bulk action:', e.detail);
        });
    </script>
</body>
</html>