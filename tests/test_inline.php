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
];

$list = new EasyList('test_list');
$list->data($users)
    ->column('id', 'ID', ['width' => '60px'])
    ->column('name', 'Name')
    ->column('email', 'Email')  
    ->column('department', 'Department')
    ->column('salary', 'Salary', ['type' => 'number', 'align' => 'right'])
    ->searchable(true)
    ->sortable(true)
    ->paginate(true, 3);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Test EasyList Inline</title>
    <link rel="stylesheet" href="../semantic/dist/semantic.min.css">
    <style>
        body { padding: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>EasyList Test - JavaScript Inline</h1>
        
        <div class="ui segment">
            <?php $list->display(); ?>
        </div>
    </div>
    
    <script src="../jquery/jquery.min.js"></script>
    <script src="../semantic/dist/semantic.min.js"></script>
    
    <!-- Include easylist.js content directly -->
    <script>
    <?php echo file_get_contents(__DIR__ . '/js/easylist.js'); ?>
    </script>
    
    <script>
        console.log('EasyListHandler available:', typeof EasyListHandler !== 'undefined');
        
        // Additional debug
        setTimeout(function() {
            console.log('After timeout - EasyListHandler available:', typeof EasyListHandler !== 'undefined');
            
            // Check if table has data
            const table = document.getElementById('test_list');
            if (table) {
                console.log('Table found');
                const rows = table.querySelectorAll('tbody tr');
                console.log('Table rows:', rows.length);
            }
        }, 1000);
    </script>
</body>
</html>