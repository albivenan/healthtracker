<?php
namespace App;

use MongoDB\Client;

class Database {
    private static $instance = null;
    private $connection;
    private $database;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $mongoConfig = $config['mongodb'];
        
        $uri = sprintf(
            'mongodb://%s:%s',
            $mongoConfig['host'],
            $mongoConfig['port']
        );
        
        $this->connection = new Client($uri);
        $this->database = $this->connection->{$mongoConfig['database']};
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