<?php

namespace HealthTracker\Controllers;

use HealthTracker\Controllers\Controller;
use Albi\Models\MedicalHistory;

class MedicalHistoryController extends Controller {
    private $medicalHistoryModel;

    public function __construct() {
        $this->medicalHistoryModel = new MedicalHistory();
    }

    public function index() {
        $userId = $this->checkAuth();
        $this->view('medical-history/index');
    }

    public function history() {
        try {
            // Log detailed session and request information
            error_log("Medical History Request Details:");
            error_log("Session ID: " . session_id());
            error_log("Session Status: " . session_status());
            error_log("Session Variables: " . print_r($_SESSION, true));
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("Request URI: " . $_SERVER['REQUEST_URI']);
            
            // Ensure session is started
            if (session_status() === PHP_SESSION_NONE) {
                error_log("Session not started. Starting now.");
                session_start();
            }

            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                error_log("No user_id found in session");
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Silakan login terlebih dahulu',
                    'debug' => [
                        'session_status' => session_status(),
                        'session_vars' => array_keys($_SESSION)
                    ]
                ], 401);
                return;
            }

            $userId = $_SESSION['user_id'];
            error_log("Authenticated User ID: " . $userId);
            
            // Fetch medical history for the user
            $result = $this->medicalHistoryModel->getHistory($userId);
            
            // Log the result
            error_log("Medical History Result: " . print_r($result, true));
            
            // Return JSON response based on the model's result
            if ($result['success']) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Riwayat medis berhasil diambil',
                    'data' => $result['data']
                ]);
            } else {
                // Log the failure details
                error_log("Medical history retrieval failed: " . ($result['error'] ?? 'Unknown error'));
                throw new \Exception($result['error'] ?? 'Gagal mengambil riwayat medis');
            }
        } catch (\Exception $e) {
            // Log error details
            error_log("Error in medical history retrieval: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            // Return error response
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Gagal mengambil riwayat medis: ' . $e->getMessage(),
                'debug' => [
                    'session_status' => session_status(),
                    'session_vars' => array_keys($_SESSION)
                ]
            ], 500);
        }
    }

    private function validateMedicalHistoryInput($data) {
        // Validate required fields
        $requiredFields = [
            'condition', 'diagnosis_date', 'symptoms', 
            'severity', 'action_type', 'status'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Missing required field: " . $field);
                throw new \Exception("Missing required field: $field");
            }
        }
        
        // Set default values for optional fields
        $data['treatment'] = $data['treatment'] ?? 'Tidak ada';
        $data['medication'] = $data['medication'] ?? '';
        
        return $data;
    }

    public function add() {
        try {
            // Check authentication
            $userId = $this->checkAuth();
            
            // Get raw input data
            $rawInput = file_get_contents('php://input');
            
            // Log raw input for debugging
            error_log("Raw input for medical history: " . $rawInput);
            
            // Decode JSON input
            $data = json_decode($rawInput, true);
            
            // Validate input
            if (!$data) {
                error_log("Invalid JSON input");
                $this->jsonResponse(['success' => false, 'message' => 'Invalid data format'], 400);
                return;
            }
            
            // Validate and sanitize input
            $validatedData = $this->validateMedicalHistoryInput($data);
            
            // Attempt to create medical history record
            $result = $this->medicalHistoryModel->create($userId, $validatedData);
            
            // Return successful response
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Riwayat medis berhasil ditambahkan',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            // Log full error details
            error_log("Error in medical history add: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            // Return error response
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Gagal menambahkan riwayat medis: ' . $e->getMessage()
            ], 500);
        }
    }

    public function get($id) {
        try {
            $userId = $this->checkAuth();
            
            // Fetch record
            $record = $this->medicalHistoryModel->getById($id);
            
            // Verify record ownership
            if (!$record || $record['user_id'] !== $userId) {
                error_log("Medical history record not found or unauthorized access");
                $this->jsonResponse(['success' => false, 'message' => 'Record not found'], 404);
                return;
            }

            $this->jsonResponse(['success' => true, 'data' => $record]);
        } catch (\Exception $e) {
            error_log("Error fetching medical history record: " . $e->getMessage());
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Gagal mengambil riwayat medis: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update($id) {
        try {
            // Check authentication
            $userId = $this->checkAuth();
            
            // Get raw input data
            $rawInput = file_get_contents('php://input');
            
            // Log raw input for debugging
            error_log("Raw input for medical history update: " . $rawInput);
            
            // Decode JSON input
            $data = json_decode($rawInput, true);
            
            // Validate input
            if (!$data) {
                error_log("Invalid JSON input for update");
                $this->jsonResponse(['success' => false, 'message' => 'Invalid data format'], 400);
                return;
            }
            
            // Validate and sanitize input
            $validatedData = $this->validateMedicalHistoryInput($data);
            
            // Attempt to update medical history record
            $result = $this->medicalHistoryModel->update($id, $userId, $validatedData);
            
            // Check update result
            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'Gagal memperbarui riwayat medis');
            }
            
            // Return successful response
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Riwayat medis berhasil diperbarui',
                'data' => $result['data']
            ]);
        } catch (\Exception $e) {
            // Log full error details
            error_log("Error in medical history update: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            // Return error response
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Gagal memperbarui riwayat medis: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id) {
        try {
            // Check authentication
            $userId = $this->checkAuth();
            
            // Attempt to delete medical history record
            $result = $this->medicalHistoryModel->delete($id, $userId);
            
            // Check delete result
            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'Gagal menghapus riwayat medis');
            }
            
            // Return successful response
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Riwayat medis berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            // Log full error details
            error_log("Error in medical history delete: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            // Return error response
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Gagal menghapus riwayat medis: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}