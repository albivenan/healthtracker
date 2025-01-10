<?php
namespace HealthTracker\Models;

use MongoDB\Client;

class Activity {
    private $collection;
    private $caloriesPerStep = 0.04; // Rata-rata kalori yang terbakar per langkah
    private $caloriesPerMinute = [
        'walking' => 4.0,    // Kalori per menit untuk jalan
        'running' => 11.0,   // Kalori per menit untuk lari
        'cycling' => 7.0,    // Kalori per menit untuk bersepeda
        'swimming' => 8.0,   // Kalori per menit untuk berenang
        'other' => 5.0      // Default kalori per menit untuk aktivitas lain
    ];

    public function __construct() {
        $client = new Client(getenv('MONGODB_URI') ?: 'mongodb://localhost:27017');
        $this->collection = $client->healthtracker->activities;
    }

    public function create($data) {
        try {
            $data['user_email'] = $_SESSION['user'];
            $data['created_at'] = new \MongoDB\BSON\UTCDateTime();
            
            // Hitung kalori yang terbakar
            $caloriesFromSteps = $data['steps'] * $this->caloriesPerStep;
            $caloriesFromExercise = $this->caloriesPerMinute[$data['exercise_type']] * $data['exercise_duration'];
            $data['calories_burned'] = $caloriesFromSteps + $caloriesFromExercise;
            
            $result = $this->collection->insertOne($data);
            return $result->getInsertedCount() > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getDailyActivity($userEmail) {
        try {
            $startOfDay = new \MongoDB\BSON\UTCDateTime(strtotime('today midnight') * 1000);
            
            $result = $this->collection->find([
                'user_email' => $userEmail,
                'created_at' => [
                    '$gte' => $startOfDay
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

    public function getWeeklyActivity($userEmail) {
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
} 