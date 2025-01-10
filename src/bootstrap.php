<?php

// Load .env
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Start session
session_start();

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting
if ($_ENV['APP_ENV'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// MongoDB connection
try {
    $mongodb_uri = $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017';
    $mongodb_client = new MongoDB\Client($mongodb_uri);
    $mongodb_database = $mongodb_client->selectDatabase($_ENV['MONGODB_DB'] ?? 'healthtracker');
    
    // Make MongoDB connection globally available
    global $mongodb;
    $mongodb = $mongodb_database;
} catch(MongoDB\Driver\Exception\Exception $e) {
    if ($_ENV['APP_DEBUG'] === 'true') {
        die("MongoDB Connection failed: " . $e->getMessage());
    } else {
        die("Database connection error. Please try again later.");
    }
}

return [
    'mongodb' => $mongodb_database
]; 