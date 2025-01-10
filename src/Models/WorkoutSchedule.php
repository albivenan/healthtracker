<?php

namespace Albi\Models;

use MongoDB\Client;
use MongoDB\Collection;

class WorkoutSchedule {
    private $collection;

    public function __construct() {
        try {
            $client = new Client("mongodb://localhost:27017");
            $this->collection = $client->healthtracker->workouts;
        } catch (\Exception $e) {
            error_log("MongoDB Connection Error: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }

    public function create($userId, $programName, $day, $exercises) {
        try {
            $document = [
                'user_id' => $userId,
                'program_name' => $programName,
                'day' => $day,
                'exercises' => array_map(function($exercise) {
                    return [
                        'name' => $exercise['name'],
                        'sets' => (int)$exercise['sets'],
                        'reps' => (int)$exercise['reps'],
                        'weight' => (float)($exercise['weight'] ?? 0),
                        'completed' => false,
                        'completed_at' => null
                    ];
                }, $exercises),
                'created_at' => new \MongoDB\BSON\UTCDateTime()
            ];

            error_log("Inserting document: " . json_encode($document)); // Debug log

            $result = $this->collection->insertOne($document);
            
            if (!$result->getInsertedId()) {
                throw new \Exception("Failed to insert document");
            }

            return $result->getInsertedId();

        } catch (\Exception $e) {
            error_log("Error in create: " . $e->getMessage());
            throw new \Exception("Failed to save workout program");
        }
    }

    public function getByUserId($userId) {
        try {
            $cursor = $this->collection->find(
                ['user_id' => $userId],
                [
                    'sort' => ['created_at' => -1]
                ]
            );

            return $cursor->toArray();

        } catch (\Exception $e) {
            error_log("Error in getByUserId: " . $e->getMessage());
            throw new \Exception("Failed to fetch workout data");
        }
    }

    public function getById($id, $userId) {
        try {
            $objectId = new \MongoDB\BSON\ObjectId($id);
            return $this->collection->findOne([
                '_id' => $objectId,
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            error_log("Error getting workout: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $userId, $programName, $day, $exercises) {
        try {
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $result = $this->collection->updateOne(
                [
                    '_id' => $objectId,
                    'user_id' => $userId
                ],
                [
                    '$set' => [
                        'program_name' => $programName,
                        'day' => $day,
                        'exercises' => array_map(function($exercise) {
                            return [
                                'name' => $exercise['name'],
                                'sets' => (int)$exercise['sets'],
                                'reps' => (int)$exercise['reps'],
                                'weight' => (float)$exercise['weight'],
                                'completed' => $exercise['completed'] ?? false,
                                'completed_at' => $exercise['completed_at'] ?? null
                            ];
                        }, $exercises),
                        'updated_at' => new \MongoDB\BSON\UTCDateTime()
                    ]
                ]
            );
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log("Error updating workout: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id, $userId) {
        try {
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $result = $this->collection->deleteOne([
                '_id' => $objectId,
                'user_id' => $userId
            ]);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log("Error deleting workout: " . $e->getMessage());
            throw $e;
        }
    }

    public function toggleExerciseCompletion($id, $userId, $exerciseIndex) {
        try {
            $workout = $this->getById($id, $userId);
            if (!$workout) {
                throw new \Exception("Workout not found");
            }

            $exercises = $workout->exercises;
            if (!isset($exercises[$exerciseIndex])) {
                throw new \Exception("Exercise not found");
            }

            $exercises[$exerciseIndex]['completed'] = !($exercises[$exerciseIndex]['completed'] ?? false);
            $exercises[$exerciseIndex]['completed_at'] = $exercises[$exerciseIndex]['completed'] ? 
                new \MongoDB\BSON\UTCDateTime() : null;

            $objectId = new \MongoDB\BSON\ObjectId($id);
            $result = $this->collection->updateOne(
                [
                    '_id' => $objectId,
                    'user_id' => $userId
                ],
                [
                    '$set' => [
                        'exercises' => $exercises
                    ]
                ]
            );

            return $result->getModifiedCount() > 0;

        } catch (\Exception $e) {
            error_log("Error toggling exercise completion: " . $e->getMessage());
            throw new \Exception("Failed to update exercise completion status");
        }
    }
} 
