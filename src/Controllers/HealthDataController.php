<?php
namespace HealthTracker\Controllers;

use HealthTracker\Models\HealthData;

class HealthDataController {
    private HealthData $healthData;

    public function __construct() {
        $this->healthData = new HealthData();
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $data = [
            'weight' => floatval($_POST['weight'] ?? 0),
            'systolic' => intval($_POST['systolic'] ?? 0),
            'diastolic' => intval($_POST['diastolic'] ?? 0)
        ];

        // Validasi data
        if ($data['weight'] <= 0 || $data['systolic'] <= 0 || $data['diastolic'] <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Data tidak valid']);
            exit;
        }

        if ($this->healthData->create($data)) {
            header('Location: /albi/dashboard');
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode(['error' => 'Gagal menyimpan data']);
        exit;
    }

    public function getWeeklyData() {
        if (!isset($_SESSION['user'])) {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

        $data = $this->healthData->getWeeklyData($_SESSION['user']);
        
        // Format data untuk grafik
        $formattedData = [
            'labels' => [],
            'weight' => [],
            'systolic' => [],
            'diastolic' => []
        ];

        foreach ($data as $record) {
            $date = $record['created_at']->toDateTime()->format('D');
            $formattedData['labels'][] = $date;
            $formattedData['weight'][] = $record['weight'];
            $formattedData['systolic'][] = $record['systolic'];
            $formattedData['diastolic'][] = $record['diastolic'];
        }

        header('Content-Type: application/json');
        echo json_encode($formattedData);
        exit;
    }
} 