<?php
namespace HealthTracker\Models;

use HealthTracker\Database\Connection;
use MongoDB\Collection;

class User {
    private Collection $collection;

    public function __construct() {
        $this->collection = Connection::getInstance()->getDatabase()->users;
    }

    public function create(array $userData): bool {
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['created_at'] = new \MongoDB\BSON\UTCDateTime();
        
        $result = $this->collection->insertOne($userData);
        return $result->getInsertedCount() > 0;
    }

    public function findByEmail(string $email) {
        return $this->collection->findOne(['email' => $email]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
} 