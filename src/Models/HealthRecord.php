<?php
namespace App\Models;

use App\Database;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class HealthRecord {
    private Collection $collection;

    public function __construct() {
        $this->collection = Database::getInstance()->getDatabase()->health_records;
    }

    public function create(array $data): bool {
        $data['user_id'] = $_SESSION['user_id'];
        $data['created_at'] = new UTCDateTime();
        
        $result = $this->collection->insertOne($data);
        return $result->getInsertedCount() > 0;
    }

    public function getLatestRecord(string $userId) {
        return $this->collection->findOne(
            ['user_id' => $userId],
            ['sort' => ['created_at' => -1]]
        );
    }

    public function getWeightHistory(string $userId, int $limit = 6) {
        $cursor = $this->collection->find(
            ['user_id' => $userId],
            [
                'sort' => ['created_at' => -1],
                'limit' => $limit,
                'projection' => ['weight' => 1, 'created_at' => 1]
            ]
        );

        $history = [];
        foreach ($cursor as $record) {
            $history[] = [
                'weight' => $record->weight,
                'date' => $record->created_at->toDateTime()->format('M')
            ];
        }
        return array_reverse($history);
    }
} 