<?php
namespace HealthTracker\Database;

use MongoDB\Client;

class Connection {
    private static $instance = null;
    private $client;
    private $database;

    private function __construct() {
        $config = require_once __DIR__ . '/../../config/database.php';
        $mongoConfig = $config['mongodb'];
        
        $connectionString = "mongodb://{$mongoConfig['host']}:{$mongoConfig['port']}";
        
        $this->client = new Client($connectionString);
        $this->database = $this->client->{$mongoConfig['database']};
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDatabase() {
        return $this->database;
    }
} 