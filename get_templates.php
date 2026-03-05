<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $templates = $db->fetchAll("
        SELECT name, type, title, description 
        FROM templates 
        WHERE is_active = 1 
        ORDER BY type, name
    ");
    
    echo json_encode([
        'success' => true,
        'templates' => $templates,
        'count' => count($templates)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to load templates',
        'templates' => []
    ]);
}
?>
