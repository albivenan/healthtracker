<?php

namespace HealthTracker\Models;

class BMI {
    private $collection;

    public function __construct() {
        global $mongodb;
        if (!$mongodb) {
            throw new \Exception('Database connection not available');
        }
        $this->collection = $mongodb->bmi_records;
    }

    public function create($userId, $data) {
        try {
            $bmi = $this->calculateBMI($data['weight'], $data['height']);
            
            $document = [
                'user_id' => $userId,
                'weight' => (float)$data['weight'],
                'height' => (float)$data['height'],
                'bmi_value' => $bmi['value'],
                'bmi_category' => $bmi['category'],
                'created_at' => new \MongoDB\BSON\UTCDateTime()
            ];

            $result = $this->collection->insertOne($document);

            if ($result->getInsertedCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'BMI berhasil disimpan',
                    'data' => [
                        'id' => (string)$result->getInsertedId(),
                        'weight' => $document['weight'],
                        'height' => $document['height'],
                        'bmi_value' => $document['bmi_value'],
                        'bmi_category' => $document['bmi_category']
                    ]
                ];
            }

            throw new \Exception('Failed to save BMI data');
        } catch (\Exception $e) {
            error_log("Error in BMI::create: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal menyimpan data BMI'
            ];
        }
    }

    public function getByUserId($userId) {
        try {
            $cursor = $this->collection->find(
                ['user_id' => $userId],
                [
                    'sort' => ['created_at' => -1],
                    'limit' => 100
                ]
            );

            $records = [];
            foreach ($cursor as $document) {
                $records[] = [
                    'id' => (string)$document['_id'],
                    'weight' => $document['weight'],
                    'height' => $document['height'],
                    'bmi_value' => $document['bmi_value'],
                    'bmi_category' => $document['bmi_category'],
                    'created_at' => $document['created_at']->toDateTime()->format('Y-m-d H:i:s'),
                    'updated_at' => $document['updated_at']->toDateTime()->format('Y-m-d H:i:s')
                ];
            }

            return [
                'success' => true,
                'data' => $records
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getByUserId: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to fetch BMI records'
            ];
        }
    }

    public function getLatest($userId) {
        try {
            $document = $this->collection->findOne(
                ['user_id' => $userId],
                [
                    'sort' => ['created_at' => -1]
                ]
            );

            if (!$document) {
                return [
                    'success' => true,
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'id' => (string)$document['_id'],
                    'weight' => (float)$document['weight'],
                    'height' => (float)$document['height'],
                    'bmi_value' => (float)$document['bmi_value'],
                    'bmi_category' => $document['bmi_category'],
                    'created_at' => $document['created_at']->toDateTime()->format('Y-m-d H:i:s')
                ]
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getLatest: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal mengambil data BMI terbaru'
            ];
        }
    }

    public function update($id, $userId, $data) {
        try {
            $bmi = $this->calculateBMI($data['weight'], $data['height']);
            
            $updateData = [
                'weight' => (float)$data['weight'],
                'height' => (float)$data['height'],
                'bmi_value' => $bmi['value'],
                'bmi_category' => $bmi['category'],
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ];

            $result = $this->collection->updateOne(
                [
                    '_id' => new \MongoDB\BSON\ObjectId($id),
                    'user_id' => $userId
                ],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Data BMI berhasil diperbarui',
                    'data' => [
                        'id' => $id,
                        'weight' => $updateData['weight'],
                        'height' => $updateData['height'],
                        'bmi_value' => $updateData['bmi_value'],
                        'bmi_category' => $updateData['bmi_category']
                    ]
                ];
            }

            return [
                'success' => false,
                'error' => 'Data BMI tidak ditemukan'
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::update: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal memperbarui data BMI'
            ];
        }
    }

    public function delete($id, $userId) {
        try {
            $result = $this->collection->deleteOne([
                '_id' => new \MongoDB\BSON\ObjectId($id),
                'user_id' => $userId
            ]);

            if ($result->getDeletedCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Data BMI berhasil dihapus'
                ];
            }

            return [
                'success' => false,
                'error' => 'Data BMI tidak ditemukan'
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::delete: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal menghapus data BMI'
            ];
        }
    }

    public function getById($id, $userId) {
        try {
            $document = $this->collection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($id),
                'user_id' => $userId
            ]);

            if (!$document) {
                return [
                    'success' => false,
                    'error' => 'Data BMI tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'id' => (string)$document['_id'],
                    'weight' => (float)$document['weight'],
                    'height' => (float)$document['height'],
                    'bmi_value' => (float)$document['bmi_value'],
                    'bmi_category' => $document['bmi_category'],
                    'created_at' => $document['created_at']->toDateTime()->format('Y-m-d H:i:s')
                ]
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getById: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal mengambil data BMI'
            ];
        }
    }

    public function getWeeklyAverage($userId) {
        try {
            $sevenDaysAgo = new \MongoDB\BSON\UTCDateTime(strtotime('-7 days') * 1000);
            
            $pipeline = [
                [
                    '$match' => [
                        'user_id' => $userId,
                        'created_at' => ['$gte' => $sevenDaysAgo]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => [
                            'year' => ['$year' => '$created_at'],
                            'month' => ['$month' => '$created_at'],
                            'day' => ['$dayOfMonth' => '$created_at']
                        ],
                        'avgBMI' => ['$avg' => '$bmi_value'],
                        'avgWeight' => ['$avg' => '$weight'],
                        'date' => ['$first' => '$created_at']
                    ]
                ],
                ['$sort' => ['date' => 1]]
            ];

            $result = $this->collection->aggregate($pipeline);
            
            $data = [];
            foreach ($result as $day) {
                $data[] = [
                    'date' => $day->date->toDateTime()->format('Y-m-d'),
                    'bmi' => round($day->avgBMI, 2),
                    'weight' => round($day->avgWeight, 2)
                ];
            }

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getWeeklyAverage: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to fetch weekly BMI average'
            ];
        }
    }

    public function getMonthlyAverage($userId) {
        try {
            $thirtyDaysAgo = new \MongoDB\BSON\UTCDateTime(strtotime('-30 days') * 1000);
            
            $pipeline = [
                [
                    '$match' => [
                        'user_id' => $userId,
                        'created_at' => ['$gte' => $thirtyDaysAgo]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => [
                            'year' => ['$year' => '$created_at'],
                            'month' => ['$month' => '$created_at'],
                            'week' => ['$week' => '$created_at']
                        ],
                        'avgBMI' => ['$avg' => '$bmi_value'],
                        'avgWeight' => ['$avg' => '$weight'],
                        'date' => ['$first' => '$created_at']
                    ]
                ],
                ['$sort' => ['date' => 1]]
            ];

            $result = $this->collection->aggregate($pipeline);
            
            $data = [];
            foreach ($result as $week) {
                $data[] = [
                    'date' => $week->date->toDateTime()->format('Y-m-d'),
                    'bmi' => round($week->avgBMI, 2),
                    'weight' => round($week->avgWeight, 2)
                ];
            }

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getMonthlyAverage: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to fetch monthly BMI average'
            ];
        }
    }

    public function getHistory($userId) {
        try {
            $cursor = $this->collection->find(
                ['user_id' => $userId],
                [
                    'sort' => ['created_at' => -1],
                    'limit' => 100
                ]
            );

            $records = [];
            foreach ($cursor as $document) {
                $records[] = [
                    'id' => (string)$document['_id'],
                    'weight' => (float)$document['weight'],
                    'height' => (float)$document['height'],
                    'bmi_value' => (float)$document['bmi_value'],
                    'bmi_category' => $document['bmi_category'],
                    'created_at' => $document['created_at']->toDateTime()->format('Y-m-d H:i:s'),
                    'date_formatted' => $document['created_at']->toDateTime()->format('d M Y'),
                    'time_formatted' => $document['created_at']->toDateTime()->format('H:i')
                ];
            }

            // Calculate progress
            if (count($records) >= 2) {
                $firstRecord = end($records); // Oldest record
                $lastRecord = $records[0]; // Newest record
                
                $weightChange = $lastRecord['weight'] - $firstRecord['weight'];
                $bmiChange = $lastRecord['bmi_value'] - $firstRecord['bmi_value'];
                
                $progress = [
                    'weight_change' => round($weightChange, 1),
                    'weight_change_percentage' => round(($weightChange / $firstRecord['weight']) * 100, 1),
                    'bmi_change' => round($bmiChange, 1),
                    'bmi_change_percentage' => round(($bmiChange / $firstRecord['bmi_value']) * 100, 1),
                    'days_tracked' => round((strtotime($lastRecord['created_at']) - strtotime($firstRecord['created_at'])) / (60 * 60 * 24)),
                    'total_records' => count($records)
                ];
            } else {
                $progress = null;
            }

            return [
                'success' => true,
                'data' => $records,
                'progress' => $progress
            ];
        } catch (\Exception $e) {
            error_log("Error in BMI::getHistory: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal mengambil riwayat BMI'
            ];
        }
    }

    private function calculateBMI($weight, $height) {
        // Convert height from cm to m
        $heightInMeters = $height / 100;
        
        // Calculate BMI
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Determine category
        $category = 'normal';
        if ($bmi < 18.5) {
            $category = 'underweight';
        } elseif ($bmi >= 25 && $bmi < 30) {
            $category = 'overweight';
        } elseif ($bmi >= 30) {
            $category = 'obese';
        }

        return [
            'value' => $bmi,
            'category' => $category
        ];
    }
}