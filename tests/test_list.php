<?php
session_start();
require_once 'autoload.php';
use EasyForm\EasyList;

// Simple test data
$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'Active'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'Inactive']
];

$list = new EasyList('test_list');
$list->data($users)
    ->column('id', 'ID')
    ->column('name', 'Name')
    ->column('email', 'Email')
    ->column('status', 'Status')
    ->searchable(true)
    ->sortable(true);

// Output only the generated script
$reflection = new ReflectionClass($list);
$method = $reflection->getMethod('renderScript');
$method->setAccessible(true);
echo $method->invoke($list);