<?php
namespace HealthTracker\Controllers;

use HealthTracker\Models\Activity;

class ActivityController {
    private Activity $activity;

    public function __construct() {
        $this->activity = new Activity();
    }

    public function store() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method tidak diizinkan']);
            exit;
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Anda harus login terlebih dahulu']);
            exit;
        }

        $data = [
            'steps' => intval($_POST['steps'] ?? 0),
            'exercise_duration' => intval($_POST['exercise_duration'] ?? 0),
            'exercise_type' => $_POST['exercise_type'] ?? ''
        ];

        // Validasi data
        if ($data['steps'] < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Jumlah langkah tidak valid']);
            exit;
        }

        if ($data['exercise_duration'] < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Durasi olahraga tidak valid']);
            exit;
        }

        if (empty($data['exercise_type'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Jenis olahraga harus dipilih']);
            exit;
        }

        try {
            if ($this->activity->create($data)) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Aktivitas berhasil dicatat',
                    'data' => $data
                ]);
                exit;
            }

            http_response_code(500);
            echo json_encode(['error' => 'Gagal menyimpan data aktivitas']);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan internal']);
            exit;
        }
    }

    public function getDailyActivity() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Anda harus login terlebih dahulu']);
            exit;
        }

        try {
            $activities = $this->activity->getDailyActivity($_SESSION['user']);
            
            // Hitung total
            $summary = [
                'total_steps' => 0,
                'total_duration' => 0,
                'total_calories' => 0,
                'activities' => []
            ];

            foreach ($activities as $activity) {
                $summary['total_steps'] += $activity['steps'];
                $summary['total_duration'] += $activity['exercise_duration'];
                $summary['total_calories'] += $activity['calories_burned'];
                $summary['activities'][] = [
                    'type' => $activity['exercise_type'],
                    'duration' => $activity['exercise_duration'],
                    'calories' => $activity['calories_burned'],
                    'time' => $activity['created_at']->toDateTime()->format('H:i')
                ];
            }

            echo json_encode($summary);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data aktivitas']);
            exit;
        }
    }

    public function getWeeklyActivity() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Anda harus login terlebih dahulu']);
            exit;
        }

        try {
            $activities = $this->activity->getWeeklyActivity($_SESSION['user']);
            
            // Format data untuk grafik
            $formattedData = [
                'labels' => [],
                'steps' => [],
                'calories' => []
            ];

            $dailyData = [];
            foreach ($activities as $activity) {
                $date = $activity['created_at']->toDateTime()->format('D');
                if (!isset($dailyData[$date])) {
                    $dailyData[$date] = [
                        'steps' => 0,
                        'calories' => 0
                    ];
                }
                $dailyData[$date]['steps'] += $activity['steps'];
                $dailyData[$date]['calories'] += $activity['calories_burned'];
            }

            foreach ($dailyData as $date => $data) {
                $formattedData['labels'][] = $date;
                $formattedData['steps'][] = $data['steps'];
                $formattedData['calories'][] = $data['calories'];
            }

            echo json_encode($formattedData);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data aktivitas']);
            exit;
        }
    }
} 