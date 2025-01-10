<?php

namespace HealthTracker\Models;

class Profile {
    private $collection;

    public function __construct() {
        $mongoClient = new \MongoDB\Client("mongodb://localhost:27017");
        $this->collection = $mongoClient->healthtracker->user_profiles;
    }

    public function getByUserId($userId) {
        try {
            $document = $this->collection->findOne(['user_id' => $userId]);
            if ($document) {
                return [
                    'id' => (string) $document->_id,
                    'user_id' => $document->user_id,
                    'name' => $document->name ?? '',
                    'email' => $document->email ?? '',
                    'phone' => $document->phone ?? '',
                    'birth_date' => isset($document->birth_date) ? 
                        $document->birth_date->toDateTime()->format('Y-m-d') : '',
                    'gender' => $document->gender ?? '',
                    'address' => $document->address ?? '',
                    'photo_url' => $document->photo_url ?? '',
                    'height' => $document->height ?? 0,
                    'weight' => $document->weight ?? 0,
                    'medical_conditions' => $document->medical_conditions ?? [],
                    'allergies' => $document->allergies ?? [],
                    'emergency_contact' => $document->emergency_contact ?? [
                        'name' => '',
                        'phone' => '',
                        'relationship' => ''
                    ]
                ];
            }
            return null;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($userId, $data) {
        try {
            $updateData = [
                'user_id' => $userId,
                'name' => $data['name'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'birth_date' => isset($data['birth_date']) ? 
                    new \MongoDB\BSON\UTCDateTime(strtotime($data['birth_date']) * 1000) : null,
                'gender' => $data['gender'] ?? '',
                'address' => $data['address'] ?? '',
                'height' => floatval($data['height'] ?? 0),
                'weight' => floatval($data['weight'] ?? 0),
                'medical_conditions' => $data['medical_conditions'] ?? [],
                'allergies' => $data['allergies'] ?? [],
                'emergency_contact' => $data['emergency_contact'] ?? [
                    'name' => '',
                    'phone' => '',
                    'relationship' => ''
                ],
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ];

            if (isset($data['photo_url'])) {
                $updateData['photo_url'] = $data['photo_url'];
            }

            $result = $this->collection->updateOne(
                ['user_id' => $userId],
                ['$set' => $updateData],
                ['upsert' => true]
            );

            return $result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updatePhoto($userId, $photoUrl) {
        try {
            $result = $this->collection->updateOne(
                ['user_id' => $userId],
                [
                    '$set' => [
                        'photo_url' => $photoUrl,
                        'updated_at' => new \MongoDB\BSON\UTCDateTime()
                    ]
                ],
                ['upsert' => true]
            );

            return $result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }
} 