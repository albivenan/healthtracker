<?php
namespace HealthTracker\Models;

use HealthTracker\Database\Connection;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class FoodJournal {
    private Collection $collection;

    public function __construct() {
        $this->collection = Connection::getInstance()->getDatabase()->food_journal;
    }

    public function create(array $data): bool {
        try {
            $data['user_email'] = $_SESSION['user'];
            $data['created_at'] = new UTCDateTime(time() * 1000);
            $data['updated_at'] = new UTCDateTime(time() * 1000);
            
            $result = $this->collection->insertOne($data);
            return $result->getInsertedCount() > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findById(string $id) {
        try {
            return $this->collection->findOne(['_id' => new ObjectId($id)]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function update(string $id, array $data): bool {
        try {
            $data['updated_at'] = new UTCDateTime();
            
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id), 'user_email' => $_SESSION['user']],
                ['$set' => $data]
            );
            
            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool {
        try {
            $result = $this->collection->deleteOne([
                '_id' => new ObjectId($id),
                'user_email' => $_SESSION['user']
            ]);
            
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getDailyJournal(string $userEmail) {
        try {
            $startOfDay = new UTCDateTime(strtotime('today midnight') * 1000);
            $endOfDay = new UTCDateTime(strtotime('tomorrow midnight') * 1000);
            
            return $this->collection->find([
                'user_email' => $userEmail,
                'created_at' => [
                    '$gte' => $startOfDay,
                    '$lt' => $endOfDay
                ]
            ], [
                'sort' => ['created_at' => -1]
            ])->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getWeeklyJournal(string $userEmail) {
        try {
            $startOfWeek = new UTCDateTime(strtotime('-7 days midnight') * 1000);
            
            return $this->collection->find([
                'user_email' => $userEmail,
                'created_at' => ['$gte' => $startOfWeek]
            ], [
                'sort' => ['created_at' => -1]
            ])->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
} 