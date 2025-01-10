<?php
namespace HealthTracker\Controllers;

use HealthTracker\Models\FoodJournal;

class FoodJournalController {
    private FoodJournal $foodJournal;

    public function __construct() {
        $this->foodJournal = new FoodJournal();
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /albi/login');
            exit;
        }

        $dailyJournal = $this->foodJournal->getDailyJournal($_SESSION['user']);
        require_once __DIR__ . '/../../views/food-journal/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: /albi/login');
            exit;
        }

        require_once __DIR__ . '/../../views/food-journal/create.php';
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
            'food_name' => $_POST['food_name'] ?? '',
            'portion' => floatval($_POST['portion'] ?? 0),
            'calories' => intval($_POST['calories'] ?? 0),
            'meal_time' => $_POST['meal_time'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validasi data
        if (empty($data['food_name']) || $data['portion'] <= 0 || $data['calories'] <= 0 || empty($data['meal_time'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Data tidak lengkap atau tidak valid']);
            exit;
        }

        try {
            if ($this->foodJournal->create($data)) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Catatan makanan berhasil disimpan',
                    'data' => $data
                ]);
                exit;
            }

            http_response_code(500);
            echo json_encode(['error' => 'Gagal menyimpan catatan makanan']);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan internal']);
            exit;
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: /albi/login');
            exit;
        }

        $journal = $this->foodJournal->findById($id);
        if (!$journal || $journal->user_email !== $_SESSION['user']) {
            header('Location: /albi/food-journal');
            exit;
        }

        require_once __DIR__ . '/../../views/food-journal/edit.php';
    }

    public function update($id) {
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
            'food_name' => $_POST['food_name'] ?? '',
            'portion' => floatval($_POST['portion'] ?? 0),
            'calories' => intval($_POST['calories'] ?? 0),
            'meal_time' => $_POST['meal_time'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validasi data
        if (empty($data['food_name']) || $data['portion'] <= 0 || $data['calories'] <= 0 || empty($data['meal_time'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Data tidak lengkap atau tidak valid']);
            exit;
        }

        try {
            if ($this->foodJournal->update($id, $data)) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Catatan makanan berhasil diperbarui',
                    'data' => $data
                ]);
                exit;
            }

            http_response_code(500);
            echo json_encode(['error' => 'Gagal memperbarui catatan makanan']);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan internal']);
            exit;
        }
    }

    public function delete($id) {
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

        try {
            if ($this->foodJournal->delete($id)) {
                http_response_code(200);
                echo json_encode(['message' => 'Catatan makanan berhasil dihapus']);
                exit;
            }

            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus catatan makanan']);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan internal']);
            exit;
        }
    }

    public function getWeeklyJournal() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Anda harus login terlebih dahulu']);
            exit;
        }

        try {
            $journals = $this->foodJournal->getWeeklyJournal($_SESSION['user']);
            
            // Format data untuk grafik
            $formattedData = [
                'labels' => [],
                'calories' => [],
                'meals' => []
            ];

            $dailyData = [];
            foreach ($journals as $journal) {
                $date = $journal['created_at']->toDateTime()->format('D');
                if (!isset($dailyData[$date])) {
                    $dailyData[$date] = [
                        'calories' => 0,
                        'meals' => 0
                    ];
                }
                $dailyData[$date]['calories'] += $journal['calories'];
                $dailyData[$date]['meals']++;
            }

            foreach ($dailyData as $date => $data) {
                $formattedData['labels'][] = $date;
                $formattedData['calories'][] = $data['calories'];
                $formattedData['meals'][] = $data['meals'];
            }

            echo json_encode($formattedData);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data catatan makanan']);
            exit;
        }
    }
} 