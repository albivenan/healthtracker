<?php
namespace App\Models;

use App\Database;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class MealPlan {
    private Collection $collection;
    private Collection $foodCollection;

    public function __construct() {
        $db = Database::getInstance()->getDatabase();
        $this->collection = $db->meal_plans;
        $this->foodCollection = $db->foods;
    }

    public function generateDailyPlan(array $userPreferences, array $healthData): array {
        // Hitung BMR (Basal Metabolic Rate)
        $bmr = $this->calculateBMR($healthData);
        
        // Ambil makanan yang sesuai dengan preferensi
        $meals = [
            'breakfast' => $this->getFoodRecommendations('breakfast', $bmr * 0.3, $userPreferences),
            'lunch' => $this->getFoodRecommendations('lunch', $bmr * 0.4, $userPreferences),
            'dinner' => $this->getFoodRecommendations('dinner', $bmr * 0.3, $userPreferences)
        ];

        $plan = [
            'user_id' => $_SESSION['user_id'],
            'date' => new UTCDateTime(),
            'meals' => $meals,
            'total_calories' => $bmr,
            'preferences' => $userPreferences
        ];

        $this->collection->insertOne($plan);
        return $plan;
    }

    private function calculateBMR(array $healthData): float {
        // Rumus Harris-Benedict
        $weight = $healthData['weight'] ?? 70;
        $height = $healthData['height'] ?? 170;
        $age = $healthData['age'] ?? 30;
        $gender = $healthData['gender'] ?? 'male';

        if ($gender === 'male') {
            return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        }

        return 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    private function getFoodRecommendations(string $mealType, float $targetCalories, array $preferences): array {
        $filter = [
            'type' => $mealType,
            'calories' => ['$lte' => $targetCalories]
        ];

        if (!empty($preferences['restrictions'])) {
            $filter['allergens'] = ['$nin' => $preferences['restrictions']];
        }

        return $this->foodCollection->find($filter, [
            'limit' => 3,
            'sort' => ['rating' => -1]
        ])->toArray();
    }

    public function getUserMealPlan(string $userId, \DateTime $date = null): ?array {
        if (!$date) {
            $date = new \DateTime();
        }

        $startOfDay = new UTCDateTime($date->setTime(0, 0)->getTimestamp() * 1000);
        $endOfDay = new UTCDateTime($date->setTime(23, 59, 59)->getTimestamp() * 1000);

        return $this->collection->findOne([
            'user_id' => $userId,
            'date' => [
                '$gte' => $startOfDay,
                '$lte' => $endOfDay
            ]
        ]);
    }
} 