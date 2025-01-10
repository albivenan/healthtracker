<?php

namespace HealthTracker\Controllers;

use Albi\Models\BMI;

class BMIController {
    private $bmi;

    public function __construct() {
        $this->bmi = new BMI();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function checkAuth() {
        if (!isset($_SESSION['user'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Unauthorized']);
                exit;
            } else {
                header('Location: /albi/login');
                exit;
            }
        }
    }

    public function index() {
        $this->checkAuth();
        require __DIR__ . '/../../Views/bmi/index.php';
    }

    public function create() {
        try {
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            // Get JSON data
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate input
            if (!isset($data['weight']) || !isset($data['height'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Berat dan tinggi badan harus diisi'
                ]);
                return;
            }

            $weight = (float)$data['weight'];
            $height = (float)$data['height'];

            // Basic validation
            if ($weight <= 0 || $height <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Berat dan tinggi badan harus lebih dari 0'
                ]);
                return;
            }

            if ($weight > 300 || $height > 300) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Nilai berat atau tinggi badan tidak valid'
                ]);
                return;
            }

            // Create BMI record
            $bmi = new BMI();
            $result = $bmi->create($_SESSION['user_id'], [
                'weight' => $weight,
                'height' => $height
            ]);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }
            
            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::create: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Terjadi kesalahan saat menyimpan data'
            ]);
        }
    }

    public function getHistory() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            $bmi = new BMI();
            $result = $bmi->getHistory($_SESSION['user_id']);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }

            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::getHistory: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Gagal mengambil riwayat BMI'
            ]);
        }
    }

    public function getLatest() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            $bmi = new BMI();
            $result = $bmi->getLatest($_SESSION['user_id']);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }

            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::getLatest: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Gagal mengambil data BMI terbaru'
            ]);
        }
    }

    public function update() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            // Get JSON data
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate input
            if (!isset($data['id']) || !isset($data['weight']) || !isset($data['height'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Data tidak lengkap'
                ]);
                return;
            }

            $id = $data['id'];
            $weight = (float)$data['weight'];
            $height = (float)$data['height'];

            // Basic validation
            if ($weight <= 0 || $height <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Berat dan tinggi badan harus lebih dari 0'
                ]);
                return;
            }

            if ($weight > 300 || $height > 300) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Nilai berat atau tinggi badan tidak valid'
                ]);
                return;
            }

            // Update BMI record
            $bmi = new BMI();
            $result = $bmi->update($id, $_SESSION['user_id'], [
                'weight' => $weight,
                'height' => $height
            ]);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(404);
            }
            
            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::update: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memperbarui data'
            ]);
        }
    }

    public function delete() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            // Get JSON data
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate input
            if (!isset($data['id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'ID tidak ditemukan'
                ]);
                return;
            }

            // Delete BMI record
            $bmi = new BMI();
            $result = $bmi->delete($data['id'], $_SESSION['user_id']);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(404);
            }
            
            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::delete: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }

    public function get() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Unauthorized'
                ]);
                return;
            }

            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'ID tidak ditemukan'
                ]);
                return;
            }

            $bmi = new BMI();
            $result = $bmi->getById($_GET['id'], $_SESSION['user_id']);

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(404);
            }

            echo json_encode($result);
        } catch (Exception $e) {
            error_log("Error in BMIController::get: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengambil data'
            ]);
        }
    }

    public function getWeeklyAverage() {
        try {
            $this->checkAuth();
            
            $data = $this->bmi->getWeeklyAverage($_SESSION['user']);

            if (!$data['success']) {
                throw new \Exception($data['error']);
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $data['data']
            ]);

        } catch (\Exception $e) {
            error_log("Error in getWeeklyAverage: " . $e->getMessage());
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getMonthlyAverage() {
        try {
            $this->checkAuth();
            
            $data = $this->bmi->getMonthlyAverage($_SESSION['user']);

            if (!$data['success']) {
                throw new \Exception($data['error']);
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $data['data']
            ]);

        } catch (\Exception $e) {
            error_log("Error in getMonthlyAverage: " . $e->getMessage());
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function getBMICategory($bmi) {
        if ($bmi < 18.5) return 'underweight';
        if ($bmi < 25) return 'normal';
        if ($bmi < 30) return 'overweight';
        return 'obese';
    }
}