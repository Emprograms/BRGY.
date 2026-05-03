<?php
header('Content-Type: application/json');

$data = [
    ['name' => 'Juan Dela Cruz', 'position' => 'Barangay Captain'],
    ['name' => 'Maria Clara', 'position' => 'Barangay Secretary'],
];

echo json_encode($data);
?>
