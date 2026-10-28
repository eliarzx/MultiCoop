<?php
$config = require __DIR__ . '/../config/database.php';
$conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
if ($conn->connect_error) {
    die('Database connection failed. Check your database configuration.');
}
