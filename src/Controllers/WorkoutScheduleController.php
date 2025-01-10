<?php

namespace HealthTracker\Controllers;

use Albi\Models\WorkoutSchedule;

class WorkoutScheduleController {
    private $workoutSchedule;

    public function __construct() {
        $this->workoutSchedule = new WorkoutSchedule();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function checkAuth() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
    }

    public function index() {
        $this->checkAuth();
        require __DIR__ . '/../../Views/workout-schedule/index.php';
    }

    public function create() {
        try {
            $this->checkAuth();

            $rawData = file_get_contents('php://input');
            error_log("Received data: " . $rawData);
            
            $data = json_decode($rawData, true);
            if (!$data) {
                throw new \Exception('Format data JSON tidak valid');
            }

            // Validasi data yang lebih ketat
            if (empty($data['program_name']) || strlen($data['program_name']) > 100) {
                throw new \Exception('Nama program tidak valid');
            }

            if (empty($data['day']) || !in_array($data['day'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])) {
                throw new \Exception('Hari tidak valid');
            }

            if (empty($data['exercises']) || !is_array($data['exercises'])) {
                throw new \Exception('Data latihan tidak valid');
            }

            foreach ($data['exercises'] as $exercise) {
                if (empty($exercise['name']) || empty($exercise['sets']) || empty($exercise['reps'])) {
                    throw new \Exception('Detail latihan tidak lengkap');
                }
            }

            $result = $this->workoutSchedule->create(
                $_SESSION['user'],
                htmlspecialchars($data['program_name']),
                $data['day'],
                $data['exercises']
            );

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Program workout berhasil disimpan',
                'id' => (string)$result,
                'data' => [
                    'program_name' => $data['program_name'],
                    'day' => $data['day'],
                    'exercises' => $data['exercises']
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Error in create: " . $e->getMessage());
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getWorkouts() {
        try {
            $this->checkAuth();

            $workouts = $this->workoutSchedule->getByUserId($_SESSION['user']);
            
            // Format data sebelum dikirim
            $formattedWorkouts = array_map(function($workout) {
                return [
                    'id' => (string)$workout['_id'],
                    'program_name' => $workout['program_name'],
                    'day' => $workout['day'],
                    'exercises' => $workout['exercises'],
                    'created_at' => isset($workout['created_at']) ? $workout['created_at'] : null
                ];
            }, $workouts);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $formattedWorkouts,
                'total' => count($formattedWorkouts)
            ]);

        } catch (\Exception $e) {
            error_log("Error in getWorkouts: " . $e->getMessage());
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getWorkout($id) {
        try {
            $this->checkAuth();

            if (empty($id)) {
                throw new \Exception('ID workout tidak valid');
            }

            $workout = $this->workoutSchedule->getById($id, $_SESSION['user']);
            
            if (!$workout) {
                throw new \Exception('Workout tidak ditemukan');
            }

            $formattedWorkout = [
                'id' => (string)$workout['_id'],
                'program_name' => $workout['program_name'],
                'day' => $workout['day'],
                'exercises' => $workout['exercises'],
                'created_at' => isset($workout['created_at']) ? $workout['created_at'] : null
            ];

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $formattedWorkout
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getMessage() === 'Workout tidak ditemukan' ? 404 : 500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update($id) {
        try {
            $this->checkAuth();

            if (empty($id)) {
                throw new \Exception('ID workout tidak valid');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new \Exception('Format data JSON tidak valid');
            }

            // Validasi data yang lebih ketat
            if (empty($data['program_name']) || strlen($data['program_name']) > 100) {
                throw new \Exception('Nama program tidak valid');
            }

            if (empty($data['day']) || !in_array($data['day'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])) {
                throw new \Exception('Hari tidak valid');
            }

            if (empty($data['exercises']) || !is_array($data['exercises'])) {
                throw new \Exception('Data latihan tidak valid');
            }

            $success = $this->workoutSchedule->update(
                $id,
                $_SESSION['user'],
                htmlspecialchars($data['program_name']),
                $data['day'],
                $data['exercises']
            );

            header('Content-Type: application/json');
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Program workout berhasil diperbarui',
                    'data' => [
                        'id' => $id,
                        'program_name' => $data['program_name'],
                        'day' => $data['day'],
                        'exercises' => $data['exercises']
                    ]
                ]);
            } else {
                throw new \Exception('Program workout tidak ditemukan');
            }
        } catch (\Exception $e) {
            $statusCode = $e->getMessage() === 'Program workout tidak ditemukan' ? 404 : 500;
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        try {
            $this->checkAuth();

            if (empty($id)) {
                throw new \Exception('ID workout tidak valid');
            }

            $success = $this->workoutSchedule->delete($id, $_SESSION['user']);
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Program workout berhasil dihapus'
                ]);
            } else {
                throw new \Exception('Program workout tidak ditemukan');
            }
        } catch (\Exception $e) {
            $statusCode = $e->getMessage() === 'Program workout tidak ditemukan' ? 404 : 500;
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
} 