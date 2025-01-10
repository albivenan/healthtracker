<?php
namespace HealthTracker\Models;

use MongoDB\Client;

class HealthData {
    private $collection;

    public function __construct() {
        $client = new Client(getenv('MONGODB_URI') ?: 'mongodb://localhost:27017');
        $this->collection = $client->healthtracker->health_data;
    }

    public function create($data) {
        try {
            $data['user_email'] = $_SESSION['user'];
            $data['created_at'] = new \MongoDB\BSON\UTCDateTime();
            
            $result = $this->collection->insertOne($data);
            return $result->getInsertedCount() > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getWeeklyData($userEmail) {
        try {
            $weekAgo = new \MongoDB\BSON\UTCDateTime((time() - (7 * 24 * 60 * 60)) * 1000);
            
            $result = $this->collection->find([
                'user_email' => $userEmail,
                'created_at' => [
                    '$gte' => $weekAgo
                ]
            ], [
                'sort' => ['created_at' => 1]
            ]);

            return $result->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getLatestData($userEmail) {
        return $this->collection->findOne(
            ['user_email' => $userEmail],
            ['sort' => ['created_at' => -1]]
        );
    }
} 