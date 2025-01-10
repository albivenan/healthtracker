<?php

namespace Albi\Models;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class MedicalHistory {
    private $collection;

    public function __construct() {
        try {
            // Check if MongoDB extension is loaded
            if (!extension_loaded('mongodb')) {
                throw new \Exception("MongoDB extension is not loaded");
            }

            // Attempt to connect with more detailed connection parameters
            $client = new Client("mongodb://localhost:27017", [
                'connectTimeoutMS' => 5000,
                'socketTimeoutMS' => 5000,
                'serverSelectionTimeoutMS' => 5000
            ]);
            
            // Verify connection
            $client->listDatabases();
            
            $this->collection = $client->healthtracker->medical_history;
        } catch (\Exception $e) {
            // Log detailed connection error
            error_log("MongoDB Connection Error: " . $e->getMessage());
            
            // Throw a more informative exception
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function create($userId, $data) {
        try {
            // Validate user ID
            if (!$userId) {
                throw new \Exception("User ID is required");
            }

            // Sanitize and validate input data
            $document = [
                'user_id' => $userId,
                'condition' => filter_var($data['condition'], FILTER_SANITIZE_STRING),
                'diagnosis_date' => new UTCDateTime(strtotime($data['diagnosis_date']) * 1000),
                'symptoms' => array_map(function($symptom) {
                    return filter_var($symptom, FILTER_SANITIZE_STRING);
                }, $data['symptoms']),
                'severity' => max(1, min(5, intval($data['severity']))), // Ensure severity is between 1-5
                'treatment' => filter_var($data['treatment'] ?? 'Tidak ada', FILTER_SANITIZE_STRING),
                'action_type' => filter_var($data['action_type'], FILTER_SANITIZE_STRING),
                'medication' => filter_var($data['medication'] ?? '', FILTER_SANITIZE_STRING),
                'status' => filter_var($data['status'], FILTER_SANITIZE_STRING),
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ];

            // Validate document before insertion
            $this->validateDocument($document);

            // Insert document
            $result = $this->collection->insertOne($document);

            // Check insertion result
            if ($result->getInsertedCount() === 0) {
                throw new \Exception("Failed to insert medical history record");
            }

            // Return successful response
            return [
                'success' => true,
                'message' => 'Riwayat medis berhasil ditambahkan',
                'data' => [
                    'id' => (string)$result->getInsertedId(),
                    'condition' => $document['condition'],
                    'diagnosis_date' => $data['diagnosis_date'],
                    'symptoms' => $document['symptoms'],
                    'severity' => $document['severity'],
                    'treatment' => $document['treatment'],
                    'action_type' => $document['action_type'],
                    'medication' => $document['medication'],
                    'status' => $document['status']
                ]
            ];
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function validateDocument($document) {
        // Additional validation rules
        $validStatuses = ['Sedang Berlangsung', 'Sembuh', 'Kronis'];
        $validActionTypes = ['self', 'doctor'];

        if (empty($document['condition'])) {
            throw new \Exception("Kondisi tidak boleh kosong");
        }

        if (count($document['symptoms']) === 0) {
            throw new \Exception("Setidaknya satu gejala harus diisi");
        }

        if (!in_array($document['status'], $validStatuses)) {
            throw new \Exception("Status tidak valid");
        }

        if (!in_array($document['action_type'], $validActionTypes)) {
            throw new \Exception("Tipe tindakan tidak valid");
        }
    }

    public function getHistory($userId) {
        try {
            // Log the user ID being queried
            error_log("Fetching medical history for user ID: " . $userId);

            // Validate user ID
            if (!$userId) {
                error_log("Invalid user ID provided");
                return [
                    'success' => false,
                    'error' => 'User ID tidak valid'
                ];
            }

            $cursor = $this->collection->find(
                ['user_id' => $userId],
                [
                    'sort' => ['diagnosis_date' => -1],
                    'limit' => 100
                ]
            );

            $records = [];
            $recordCount = 0;
            foreach ($cursor as $document) {
                $recordCount++;
                try {
                    $records[] = [
                        'id' => (string)$document['_id'],
                        'condition' => $document['condition'] ?? 'Tidak diketahui',
                        'diagnosis_date' => $document['diagnosis_date'] instanceof UTCDateTime 
                            ? $document['diagnosis_date']->toDateTime()->format('Y-m-d') 
                            : 'Tanggal tidak valid',
                        'symptoms' => $document['symptoms'] ?? [],
                        'severity' => $document['severity'] ?? 0,
                        'treatment' => $document['treatment'] ?? 'Tidak ada',
                        'action_type' => $document['action_type'] ?? '',
                        'medication' => $document['medication'] ?? '',
                        'status' => $document['status'] ?? 'Ongoing',
                        'created_at' => $document['created_at'] instanceof UTCDateTime 
                            ? $document['created_at']->toDateTime()->format('Y-m-d H:i:s') 
                            : 'Tanggal tidak valid'
                    ];
                } catch (\Exception $recordError) {
                    error_log("Error processing record: " . $recordError->getMessage());
                }
            }

            // Log record count
            error_log("Retrieved " . $recordCount . " medical history records");

            return [
                'success' => true,
                'data' => $records
            ];
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update($id, $userId, $data) {
        try {
            $updateData = [
                'condition' => $data['condition'],
                'diagnosis_date' => new UTCDateTime(strtotime($data['diagnosis_date']) * 1000),
                'symptoms' => $data['symptoms'],
                'severity' => intval($data['severity']),
                'treatment' => $data['treatment'],
                'action_type' => $data['action_type'],
                'medication' => $data['medication'],
                'status' => $data['status'],
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->collection->updateOne(
                [
                    '_id' => new ObjectId($id),
                    'user_id' => $userId
                ],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Riwayat medis berhasil diperbarui',
                    'data' => array_merge(['id' => $id], $data)
                ];
            }

            return [
                'success' => false,
                'error' => 'Data riwayat medis tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function delete($id, $userId) {
        try {
            $result = $this->collection->deleteOne([
                '_id' => new ObjectId($id),
                'user_id' => $userId
            ]);

            if ($result->getDeletedCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Riwayat medis berhasil dihapus'
                ];
            }

            return [
                'success' => false,
                'error' => 'Data riwayat medis tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getById($id, $userId) {
        try {
            $document = $this->collection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => $userId
            ]);

            if (!$document) {
                return [
                    'success' => false,
                    'error' => 'Data riwayat medis tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'id' => (string)$document['_id'],
                    'condition' => $document['condition'],
                    'diagnosis_date' => $document['diagnosis_date']->toDateTime()->format('Y-m-d'),
                    'symptoms' => $document['symptoms'] ?? [],
                    'severity' => $document['severity'] ?? 0,
                    'treatment' => $document['treatment'],
                    'action_type' => $document['action_type'] ?? '',
                    'medication' => $document['medication'] ?? '',
                    'status' => $document['status'] ?? 'Ongoing',
                    'created_at' => $document['created_at']->toDateTime()->format('Y-m-d H:i:s')
                ]
            ];
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(\Exception $e) {
        // Log the full error details
        error_log("Error in medical history operation: " . $e->getMessage());
        error_log("Trace: " . $e->getTraceAsString());
        
        // Return a structured error response
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    
    }
}